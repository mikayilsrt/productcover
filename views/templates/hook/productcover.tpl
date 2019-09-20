{foreach from=$product_cover item=cover}
  {if $cover.cover_image}
    <img class="product_cover_image" src="{$urls.img_prod_url}../scenes/thumbs/{$cover.cover_image}" style="width:100%;position:absolute;left:0;right:0;" />
  {/if}
{/foreach}
