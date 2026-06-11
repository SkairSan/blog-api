<?php

namespace App\Orchid\Screens;

use App\Models\Post;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PostListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return ['posts' => Post::with('user')->paginate(10),];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Posts';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [Link::make('Create post')->icon('plus')->route('platform.posts.create'),];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('posts', [
                TD::make('id', 'ID')
                    ->render(fn (Post $post) => $post->id)
                    ->sort(),
                
                TD::make('title', 'Title')
                    ->render(fn (Post $post) => 
                        Link::make($post->title)
                            ->route('platform.posts.edit', $post)
                    ),
                
                TD::make('user.name', 'Author')
                    ->render(fn (Post $post) => $post->user->name ?? '—'),
                
                TD::make('created_at', 'Created at')
                    ->render(fn (Post $post) => $post->created_at->format('d.m.Y H:i'))
                    ->sort(),
            ]),
        ];
    }
}
