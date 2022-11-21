<template>
  <div class="mt-2">
    <input
      type="hidden"
      name="tags"
      :value="tagsJson"
    >
    <vue-tags-input
      v-model="tag"
      :tags="tags"
      placeholder="１タグのみ検索できます"
      :add-on-key="[13, 32]"
      :autocomplete-items="filteredItems"
      @tags-changed="newTags => tags = newTags"
      :max-tags="1"
      :autocomplete-min-length="2"
    />
    
    <search-article-inifinite
     v-if="this.word != ''"
      page-type='search'
     :authorized-id=this.authorizedId
     :authorized-check=this.authorizedCheck
     :word=this.word
     >
    </search-article-inifinite>
    <div v-if="this.receiveRandomItems.length > 0">
      <div v-if="this.word == ''" class="mt-5">
        <div class="text-center font-weight-bold mb-1">おすすめのタグ</div>
        <p class="my-4" v-for="item in this.receiveRandomItems" v-bind:key="item.tag" @click="setup(item.tag)">
          {{ item.text }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
  import VueTagsInput from '@johmun/vue-tags-input';
  import SearchArticleInifinite from './SearchArticleInifinite'

  export default {
    components: {
      VueTagsInput,
      SearchArticleInifinite
    },
    props: {
      authorizedId: {
        type: String,
        default: 0,
      },
      authorizedCheck: {
        type: Boolean,
        default: false,
      },
      autocompleteItems: {
        type: Array,
        default: [],
      },
      randomItems: {
        type: Array,
        default: [],
      },
    },
    data() {
      return {
        tag: '',
        tags: [],
        jsontags: '',
        word: '',
        receiveRandomItems: this.randomItems
      };
    },
    methods: {
      setup (tag) {
        this.word = tag;
        this.tags = [{ text : tag }];
        this.tagsJson();
      },
    },
    computed: {
      filteredItems() {
        return this.autocompleteItems.filter(i => {
          return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
        });
      },
      tagsJson() {
        this.jsontags = JSON.stringify(this.tags).replace(/\(.+?\)/g, "");
        if (JSON.parse(this.jsontags)[0]) {
          this.word = JSON.parse(this.jsontags)[0].text;
        }
        return this.jsontags;
      },
    },
  };
</script>
<style lang="css" scoped>
  .vue-tags-input {
    max-width: inherit;
  }
</style>
<style lang="css">
  .vue-tags-input .ti-tag {
    background: transparent;
    border: 1px solid #747373;
    color: #747373;
    margin-right: 4px;
    border-radius: 0px;
    font-size: 13px;
  }
  .vue-tags-input .ti-tag::before {
    content: "#";
  }
</style>
