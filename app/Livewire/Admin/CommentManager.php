<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;

class CommentManager extends Component
{
    use WithPagination;

    public function approveComment($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment) {
            $comment->status = 'approved';
            $comment->save();
            $this->dispatch('show-success', message: 'Bình luận đã được phê duyệt.');
        }
    }

    public function rejectComment($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment) {
            $comment->status = 'rejected';
            $comment->save();
            $this->dispatch('show-success', message: 'Bình luận đã bị từ chối.');
        }
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment) {
            $comment->delete();
            $this->dispatch('show-success', message: 'Bình luận đã được xóa thành công!');
        }
    }

    public function render()
    {
        $comments = Comment::with('user', 'post')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.comment-manager', [
            'comments' => $comments,
        ])->layout('layouts.app');
    }
}