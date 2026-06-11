<?php

namespace App\Orchid\Screens;

use App\Models\Post;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PostEditScreen extends Screen
{
    public $post;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Post $post): iterable
    {
        return [
            'post' => $post,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->post->exists ? 'Edit post' : 'Create post';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Save')->icon('save')->method('save'),
            Button::make('Delete')->icon('trash')->method('remove')
                ->canSee($this->post->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('post.title')->title('Title')->required(),
                TextArea::make('post.text')->title('Text')->required()->rows(10),
            ]),
        ];
    }


    public function save(Post $post, Request $request)
    {
        $post->fill($request->get('post'));
    
        if (!$post->exists) {
            $post->user_id = auth()->id();
        }
    
        $post->save();
    
        Toast::info('Post saved');
        return redirect()->route('platform.posts');
    }


    public function remove(Post $post)
    {
        $post->delete();
        Toast::info('Post deleted');
        return redirect()->route('platform.posts');
    }
}
