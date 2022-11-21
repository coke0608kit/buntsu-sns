<template>
  <div>
    <div class="row">
      <div v-for="tweet in tweets" :key="tweet.id" class="col-4 p-0" style="border: 1px solid black;">
        <a class="text-dark" :href="tweet.showUrl">
          <img style="width:100%;" :alt=tweet.image :src="tweet.image">
        </a>
      </div>
    </div>
    <infinite-loading @infinite="fetchTweets"></infinite-loading>
  </div>
</template>

<script>
  import InfiniteLoading from 'vue-infinite-loading'
  import ArticleLike from './ArticleLike'
  import TimeLineFollowButton from './TimeLineFollowButton'
  export default {
    components: {
        InfiniteLoading,
        ArticleLike,
        TimeLineFollowButton,
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
      pageType: {
        type: String,
        default: '',
      },
      user: {
        type: String,
        default: '',
      },
      test: {
        type: String,
        default: '',
      }
    },
    data() {
      return {
        page: 0, // ツイートテーブルのOffsetを指定するための変数
        tweets: [], // ツイートを格納
        editUrl: '',
        showUrl: '',
        tagUrl: '',
        likeUrl: '',
        followUrl: '',
        value: {},
        checkLike: false,
      }
    },
    methods: {
        fetchTweets($state) {
            let fetchedTweetIdList = this.fetchedTweetIdList(); // すでに取得したツイートのIDリストを取得

            axios.get('/tweet', {
                params: {
                    fetchedTweetIdList: JSON.stringify(fetchedTweetIdList),
                    page: this.page,
                    pageType: this.pageType,
                    user: this.user,
                    test: this.test,
                }
            })
            .then(response => {
                if (response.data.tweets.length) {
                    this.page++;
                    response.data.tweets.forEach (value => {
                        value.user.url = '/users/' + value.user.name
                        this.editUrl = '/articles/' + value.id + '/edit'
                        value.editUrl = this.editUrl
                        this.showUrl = '/articles/' + value.id
                        value.showUrl = this.showUrl
                        this.likeUrl = '/articles/' + value.id + '/like'
                        value.likeUrl = this.likeUrl
                        this.followUrl ='/users/' + value.user.name + '/follow'
                        value.followUrl = this.followUrl
                        value.user.checkLike = false
                        value.likes.forEach (like => {
                          if (like.id == this.authorizedId) {
                            this.checkLike = true
                            value.user.checkLike = this.checkLike
                          }
                        });
                        value.tags.forEach (tag => {
                          this.tagUrl = '/tags/' + tag.name
                          tag.tagUrl = this.tagUrl
                          tag.name = '#' + tag.name
                        });
                        this.tweets.push(value);
                    });
                    $state.loaded();
                } else {
                    $state.complete();
                }
            })
            .catch(error => {
                console.log(error);
            })

        },

        fetchedTweetIdList() {
            let fetchedTweetIdList = [];
            for (let i = 0; i < this.tweets.length; i++) {
                fetchedTweetIdList.push(this.tweets[i].id);
            }
            return fetchedTweetIdList;
        }
    }
  }
</script>