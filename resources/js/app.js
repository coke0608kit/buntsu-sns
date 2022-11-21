import './bootstrap'
import './fontawesome'
import Vue from 'vue'
import ArticleLike from './components/ArticleLike'
import ArticleTagsInput from './components/ArticleTagsInput'
import SearchArticleTagsInput from './components/SearchArticleTagsInput'
import SearchUserTagsInput from './components/SearchUserTagsInput'
import ArticlePhotosInput from './components/ArticlePhotosInput'
import FollowButton from './components/FollowButton'
import ArticleInifinite from './components/ArticleInifinite'
import SearchArticleInifinite from './components/SearchArticleInifinite'
import SearchUserInifinite from './components/SearchUserInifinite'
import ArticleInifiniteAll from './components/ArticleInifiniteAll'
import ZipcodeSearch from './components/ZipcodeSearch'
import BirthdaySetting from './components/BirthdaySetting'
import FlashMessage from './components/FlashMessage'
import BlockButton from './components/BlockButton'
import UserHobbyInifinite from './components/UserHobbyInifinite'
import PayjpForm from './components/PayjpForm'
import DynamicPrice from './components/DynamicPrice'
import QutteReader from './components/QutteReader'
import QrcodeStream from 'vue-qrcode-reader'
import PreQutteReader from './components/PreQutteReader'
import BottleQutteReader from './components/BottleQutteReader'
import BottlePreQutteReader from './components/BottlePreQutteReader'
import ConfirmQutteReader from './components/ConfirmQutteReader'
import ConfirmPreQutteReader from './components/ConfirmPreQutteReader'
import TestPreQutteReader from './components/TestPreQutteReader'
import TestQutteReader from './components/TestQutteReader'
import QuestionsInifinite from './components/QuestionsInifinite'
import InformationInifinite from './components/InformationInifinite'
import SubmitArticle from './components/SubmitArticle'
import SubmitProfile from './components/SubmitProfile'

const app = new Vue({
  el: '#app',
  components: {
    ArticleLike,
    ArticleTagsInput,
    SearchArticleTagsInput,
    SearchUserTagsInput,
    ArticlePhotosInput,
    FollowButton,
    ArticleInifinite,
    SearchArticleInifinite,
    SearchUserInifinite,
    ArticleInifiniteAll,
    ZipcodeSearch,
    BirthdaySetting,
    FlashMessage,
    BlockButton,
    UserHobbyInifinite,
    PayjpForm,
    DynamicPrice,
    QutteReader,
    QrcodeStream,
    PreQutteReader,
    BottleQutteReader,
    BottlePreQutteReader,
    ConfirmQutteReader,
    ConfirmPreQutteReader,
    TestPreQutteReader,
    TestQutteReader,
    QuestionsInifinite,
    InformationInifinite,
    SubmitArticle,
    SubmitProfile
}})
