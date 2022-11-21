<template>
  <div>
    <div v-for="tweet in tweets" :key="tweet.id">
      <div class="card mt-3" v-bind:style="{background: tweet.user.official}">
        <div class="card-body d-flex flex-row pb-0">
          <a :href="tweet.user.url" class="text-dark mr-3">
              <img v-if="tweet.user.profile.icon !== null" style="width:45px;" :alt="tweet.user.nickname" :src="tweet.user.profile.icon">
              <i v-else class="fas fa-user-circle fa-3x"></i>
          </a>
          <div>
            <div class="font-weight-bold">
              <a :href="tweet.user.url" class="text-dark">
                  {{ tweet.user.nickname }}
              </a>
            </div>
            <div class="font-weight-lighter">
              {{ tweet.created_at }}
            </div>
          </div>

          <!-- dropdown -->
            <div v-if="authorizedId == tweet.user.id" class="ml-auto card-text">
              <div class="dropdown">
                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <button type="button" class="btn btn-link text-muted m-0 p-2">
                    <i class="fas fa-ellipsis-v"></i>
                  </button>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" :href="tweet.editUrl">
                    <i class="fas fa-pen mr-1"></i>投稿を更新する
                  </a>
                </div>
              </div>
            </div>
          <!-- dropdown -->

        </div>
        <div class="card-body pt-0 pb-2">
          <h3 class="h4 card-title">
          </h3>
          <div class="card-text">
            <a class="text-dark" :href="tweet.showUrl">
              <img style="width:100%;" alt="再読み込みすると表示するかも..." :src="tweet.image">
            </a>
          </div>
        </div>
        <div class="card-body pt-0 pb-2 pl-3">
          <div class="card-text">
            <article-like
              :initial-is-liked-by="tweet.user.checkLike"
              :initial-count-likes='tweet.likes.length'
              :authorized='authorizedCheck'
              :endpoint='tweet.likeUrl'
            >
            </article-like>
          </div>
        </div>

        <div class="card-body pt-0 pb-4 pl-3">
          <div class="card-text line-height">
            <span v-for="tag in tweet.tags" :key="tag.id" style="display: inline-flex;">
              <a :href="tag.tagUrl" class="border p-1 mr-1 mt-1 text-muted">{{ tag.name }}</a>
            </span>
          </div>
        </div>
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
      },
      word: {
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
        tmp: ''
      }
    },
    watch: {
      word: function () {
        this.tweets = [];
        this.page = 0;
        this.fetchTweets();
      }
    },
    methods: {
        fetchTweets($state) {
            let fetchedTweetIdList = this.fetchedTweetIdList(); // すでに取得したツイートのIDリストを取得

            axios.get('/searchArticle', {
                params: {
                    fetchedTweetIdList: JSON.stringify(fetchedTweetIdList),
                    page: this.page,
                    pageType: this.pageType,
                    user: this.user,
                    test: this.test,
                    word: this.word,
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
                        if (value.user.official == 1) {
                          value.user.official = '#FFF2CC'
                        }	
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