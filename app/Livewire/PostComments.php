<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostComments extends Component
{
    use WithPagination, AuthorizesRequests;

    public Post $post;
    public $newCommentText = '';
    public $replyToId = null;

    protected $listeners = ['commentAdded' => '$refresh'];

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    protected function rules()
    {
        return [
            'newCommentText' => 'required|string|min:3|max:1000',
        ];
    }

    public function postComment()
    {
        if (!auth()->check()) {
            session()->flash('comment_error', 'Bạn cần đăng nhập để bình luận.');
            return;
        }

        $this->validate();

        $status = auth()->user()->isAdmin() ? 'approved' : 'pending';

        try {
            $this->post->comments()->create([
                'user_id' => auth()->id(),
                'content' => $this->newCommentText,
                'parent_id' => $this->replyToId,
                'status' => $status,
            ]);

            $this->newCommentText = '';
            $this->replyToId = null;

            if ($status === 'pending') {
                session()->flash('comment_message', 'Bình luận của bạn đã được gửi và đang chờ phê duyệt.');
            } else {
                session()->flash('comment_message', 'Bình luận của bạn đã được đăng!');
            }

            $this->dispatch('commentAdded');
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('comment_error', 'Có lỗi xảy ra khi gửi bình luận. Vui lòng thử lại.');
        }
    }

    public function replyTo($commentId)
    {
        $this->replyToId = $commentId;
        $this->dispatch('focusCommentInput');
    }

    public function cancelReply()
    {
        $this->replyToId = null;
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->where(function ($query) {
                $query->where('status', 'approved')
                      ->orWhere(function ($subQuery) {
                          if (Auth::check()) {
                              $subQuery->where('user_id', Auth::id())
                                       ->whereIn('status', ['pending', 'rejected']);
                          }
                      });
            })
            ->whereNull('parent_id')
            ->with(['user', 'replies' => function ($query) {
                $query->where(function ($query) {
                    $query->where('status', 'approved')
                          ->orWhere(function ($subQuery) {
                              if (Auth::check()) {
                                  $subQuery->where('user_id', Auth::id())
                                           ->whereIn('status', ['pending', 'rejected']);
                              }
                          });
                })->with('user');
            }])
            ->latest()
            ->paginate(5);

        return view('livewire.post-comments', [
            'comments' => $comments,
        ]);
    }
}