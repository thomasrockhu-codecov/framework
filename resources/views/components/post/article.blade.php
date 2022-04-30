<article aria-label="Article" id="{{ Hyde::uriPath() ?? '' }}posts/{{ $post->slug }}" itemscope itemtype="https://schema.org/Article"
    @class(['post-article mx-auto prose dark:prose-invert', 'torchlight-enabled' => Hyde\Framework\Features::hasTorchlight()])>
    <meta itemprop="identifier" content="{{ $post->slug }}">
    @if(Hyde::uriPath())
    <meta itemprop="url" content="{{ Hyde::uriPath('posts/' . $post->slug) }}">
    @endif
    
    <header aria-label="Header section" role="doc-pageheader">
        <h1 itemprop="headline" class="mb-4">{{ $title ?? 'Blog Post' }}</h1>
		<div id="byline" aria-label="About the post" role="doc-introduction">
            @includeWhen(isset($post->date), 'hyde::components.post.date')
		    @includeWhen(isset($post->author), 'hyde::components.post.author')
            @includeWhen(isset($post->category), 'hyde::components.post.category')
        </div>
    </header>
    @includeWhen(isset($post->image), 'hyde::components.post.image')
    <div aria-label="Article body" itemprop="articleBody">
        {!! $markdown !!}
    </div>
    <span class="sr-only">End of article</span>
</article>
