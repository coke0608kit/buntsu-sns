@csrf
<div class="form-group">
    @if ($article ?? '')
      <div class="text-center my-1">
        編集
      </div>
      <img src={{ $article->image }} style="width: 100%; height: auto; border: 1px solid gray">
      <p style="text-align:center;color:red">※画像は編集できません。</p>
    @else
      <div class="text-center my-1">
        新規投稿
      </div>
      <article-photos-input
      >  
      </article-photos-input>
    @endif
</div>
<div class="form-group">
  <article-tags-input
    :initial-tags='@json($tagNames ?? [])'
    :autocomplete-items='@json($allTagNames ?? [])'
  >
  </article-tags-input>
</div>
