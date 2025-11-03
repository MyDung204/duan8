<?php

namespace App\Livewire\Admin;

use App\Models\Tag;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class TagManager extends Component
{
    use WithPagination;

    public $name;
    public $slug;
    public $tagId;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:tags,slug',
    ];

    public function render()
    {
        $tags = Tag::latest()->paginate(10);
        return view('livewire.admin.tag-manager', [
            'tags' => $tags,
        ]);
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function saveTag()
    {
        if ($this->isEditing) {
            $this->rules['slug'] .= ',' . $this->tagId;
        }

        $this->validate();

        Tag::updateOrCreate(
            ['id' => $this->tagId],
            [
                'name' => $this->name,
                'slug' => Str::slug($this->slug),
            ]
        );

        session()->flash('message', $this->isEditing ? 'Tag updated successfully.' : 'Tag created successfully.');

        $this->resetInput();
    }

    public function editTag($id)
    {
        $tag = Tag::findOrFail($id);
        $this->tagId = $id;
        $this->name = $tag->name;
        $this->slug = $tag->slug;
        $this->isEditing = true;
    }

    public function deleteTag($id)
    {
        Tag::findOrFail($id)->delete();
        session()->flash('message', 'Tag deleted successfully.');
    }

    public function resetInput()
    {
        $this->name = null;
        $this->slug = null;
        $this->tagId = null;
        $this->isEditing = false;
    }
}