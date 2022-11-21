<template>
  <div>
    <div class="card mt-3" v-for="user in users" :key="user.id" v-bind:style="{background: user.official}">
      <div class="card-body pb-0">
        <div class="d-flex flex-row">
          <a :href="user.url" class="text-dark">
              <img v-if="user.profile.icon !== null" class="mr-2" style="width: 45px;" :src="user.profile.icon" :alt="user.nickname">
              <i v-else class="fas fa-user-circle fa-3x mr-1"></i>
          </a>
          <h2 class="h5 card-title m-0">
            <a :href="user.url" class="text-dark">
              {{ user.nickname }}
            </a>
            <p class="card-text">
              {{ user.profile.pref }} {{ user.profile.age }} <template v-if="user.profile.gender == 1">男性</template><template v-else-if="user.profile.gender == 2">女性</template><template v-else >未回答</template> <template v-if="user.plan == 'free'">フリー</template><template v-else-if="user.plan == 'lite'">ライト</template><template v-else-if="user.plan == 'standard'">スタンダード</template>
            </p>
          </h2>
          <follow-button
            v-if="!user.isMe"
            class="ml-auto"
            :initial-is-followed-by='user.isFollowedBy'
            :authorized='authorizedCheck'
            :endpoint="user.followUrl"
          >
          </follow-button> 
        </div>
      </div>
      <div class="card-body py-0">
        <a :href="user.url" class="text-dark">
          <div class="card-text" style="white-space: pre-line;">
            {{ user.profile.profile }}
          </div>
        </a>
      </div>
      
      <div v-if="user.canSend && !user.isMe">
        <div class="card-body pb-0 d-flex flex-row" v-if="user.profile.zipcode1 !== null && user.profile.zipcode2 !== null && user.profile.address1 !== null && user.profile.address2 !== null">
          <div class="m-0 card-text">
            <p class="mb-0">
              <template v-if="user.profile.status == 1">
                募集中！
              </template>
              <template v-if="user.profile.status == 2">
                募集停止
              </template>
            </p>
            <p class="mb-0" v-if="user.profile.condition != null">
              <template v-if="user.profile.condition == 1">
                まったり派
              </template>
              <template v-if="user.profile.condition == 2">
                テキパキ派
              </template>
            </p>
          </div>
        </div>
      </div>

      <div class="card-body pb-0 pl-3">
        <div class="card-text line-height">
          <span  v-for="hobby in user.hobbies" :key="hobby.id" style="display: inline-flex;">
            <a :href="hobby.hobbyUrl" class="border p-1 mr-1 mt-1 text-muted">
              {{ hobby.name }}
            </a>
          </span>
        </div>
      </div>

      <div class="card-body pt-0 d-flex ">
        <div class="card-text">
            {{ user.countFollowings }} フォロー
            {{ user.countFollowers }} フォロワー
        </div>
      </div>
    </div>
    <infinite-loading @infinite="fetchUsers"></infinite-loading>
  </div>
</template>

<script>
  import InfiniteLoading from 'vue-infinite-loading'
  import TimeLineFollowButton from './TimeLineFollowButton'
  import FollowButton from './FollowButton'
  export default {
    components: {
        InfiniteLoading,
        TimeLineFollowButton,
        FollowButton
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
      pref: {
        type: String,
        default: '',
      },
      age1: {
        type: Number,
        default: '',
      },
      age2: {
        type: Number,
        default: '',
      },
      male: {
        type: Boolean,
        default: '',
      },
      female: {
        type: Boolean,
        default: '',
      },
      none: {
        type: Boolean,
        default: '',
      },
      status1: {
        type: Boolean,
        default: '',
      },
      status2: {
        type: Boolean,
        default: '',
      },
      condition1: {
        type: Boolean,
        default: '',
      },
      condition2: {
        type: Boolean,
        default: '',
      },
      keyword: {
        type: String,
        default: '',
      },
      hobbies: {
        type: undefined,
        default: [],
      },
      check: {
        type: Number,
        default: 0,
      },
      plan1: {
        type: Boolean,
        default: '',
      },
      plan2: {
        type: Boolean,
        default: '',
      },
      plan3: {
        type: Boolean,
        default: '',
      },
    },
    data() {
      return {
        page: 0,
        users: [],
        hobbyUrl: '',
        followUrl: '',
        value: {},
        count: '0',
      }
    },
    watch: {
      check: function () {
        this.users = [];
        this.page = 0;
        this.fetchUsers();
      }
    },
    methods: {
        fetchUsers($state) {
            let fetchedUserIdList = this.fetchedUserIdList();

            axios.get('/searchUser', {
                params: {
                    fetchedUserIdList: JSON.stringify(fetchedUserIdList),
                    page: this.page,
                    pageType: this.pageType,
                    user: this.user,
                    pref: this.pref,
                    age1: this.age1,
                    age2: this.age2,
                    male: this.male,
                    female: this.female,
                    none: this.none,
                    status1: this.status1,
                    status2: this.status2,
                    condition1: this.condition1,
                    condition2: this.condition2,
                    keyword: this.keyword,
                    hobbies: this.hobbies,
                    plan1: this.plan1,
                    plan2: this.plan2,
                    plan3: this.plan3,
                }
            })
            .then(response => {
              this.count = response.data.count;
              this.$emit("counter", this.count);
              if (response.data.count == 0) {
                this.users = [];
              }
              if (response.data.users.length) {
                if (response.data.page == 0) {
                  this.users = [];
                }
                  this.page++;
                  response.data.users.forEach (value => {
                      value.url = '/users/' + value.name
                      this.followUrl ='/users/' + value.name + '/follow'
                      value.followUrl = this.followUrl
                      if (value.official == 1) {
                        value.official = '#FFF2CC'
                      }	
                      value.hobbies.forEach (hobby => {
                        this.hobbyUrl = '/hobbies/' + hobby.name
                        hobby.hobbyUrl = this.hobbyUrl
                        hobby.name = '#' + hobby.name
                      });
                      this.users.push(value);
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
        fetchedUserIdList() {
            let fetchedUserIdList = [];
            for (let i = 0; i < this.users.length; i++) {
                fetchedUserIdList.push(this.users[i].id);
            }
            return fetchedUserIdList;
        }
    }
  }
</script>