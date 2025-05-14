<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatController extends Controller
{

    public function chatList()
    {
        $matricnum = Auth::user()->matricnum;

        $conversations = Conversation::where('user1_matricnum', $matricnum)
            ->orWhere('user2_matricnum', $matricnum)
            ->with(['user1', 'user2', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->latest()
            ->get();

        return view('chat.list', compact('conversations'));
    }

    public function chatWithSeller($seller_matricnum)
    {
        $user = Auth::user();
        $seller = User::where('matricnum', $seller_matricnum)->firstOrFail();
    
        $matricnums = [$user->matricnum, $seller->matricnum];
        sort($matricnums); // Ensure order is consistent
    
        // Get or create the conversation
        $conversation = Conversation::firstOrCreate([
            'user1_matricnum' => $matricnums[0],
            'user2_matricnum' => $matricnums[1],
        ]);
    
        // Mark all unread messages for the logged-in user as read
        Message::where('conversation_id', $conversation->id)
            ->where('receiver_matricnum', $user->matricnum)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    
        // Fetch the latest 50 messages
        $messages = $conversation->messages()->with('sender')->latest()->take(50)->get()->reverse();
    
        return view('chat.conversation', compact('seller', 'conversation', 'messages'));
    }
    


    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $conversationId = $request->input('conversation_id');
        $conversation = Conversation::findOrFail($conversationId);

        $senderMatricnum = Auth::user()->matricnum;

        // 🔥 Detect receiver: the other participant
        $receiverMatricnum = ($conversation->user1_matricnum === $senderMatricnum)
            ? $conversation->user2_matricnum
            : $conversation->user1_matricnum;

        $message = new Message();
        $message->conversation_id = $conversationId;
        $message->sender_matricnum = $senderMatricnum;
        $message->receiver_matricnum = $receiverMatricnum; // ✅ Set receiver matricnum!

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileKey = 'chat_photos/' . time() . '-' . $file->getClientOriginalName();
            Storage::disk('s3')->put($fileKey, file_get_contents($file));

            $imageUrl = 'https://'
                . config('filesystems.disks.s3.bucket')
                . '.s3.'
                . config('filesystems.disks.s3.region')
                . '.amazonaws.com/'
                . $fileKey;

            $message->photo = $imageUrl;
        }

        $message->message = $request->input('message');
        $message->save();
        // Fire the MessageSent event to broadcast the message to the other user
        broadcast(new MessageSent($message));

        return back();
    }

    public function deleteConversation($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)->get();

        foreach ($messages as $message) {
            if ($message->photo && str_contains($message->photo, '.amazonaws.com')) {
                $photoPath = parse_url($message->photo, PHP_URL_PATH);
                $photoKey = ltrim($photoPath, '/');
                Storage::disk('s3')->delete($photoKey);
            }
        }

        Message::where('conversation_id', $conversationId)->delete();
        Conversation::where('id', $conversationId)->delete();

        return back()->with('success', 'Conversation deleted successfully.');
    }
}
