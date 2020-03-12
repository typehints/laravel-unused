<template>
  <div>
    <div class="mb-4">
      <div>
        <label
          for="email"
          class="block text-sm font-medium leading-5 text-gray-700"
          >Search By Route</label
        >
        <div class="mt-1 relative rounded-md shadow-sm">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
          >
            <svg
              class="fill-current h-5 w-5 text-gray-400"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 512 512"
            >
              <path
                d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"
              />
            </svg>
          </div>
          <input
            v-model="search"
            class="bg-white border rounded px-2 py-2 block w-full pl-10 sm:text-sm sm:leading-5"
            placeholder="home"
          />
        </div>
      </div>
    </div>

    <div class="fixed-height overflow-auto">
      <div class="justify-center flex w-full">
        <table class="table-auto w-full rounded shadow">
          <tbody v-for="(usedView, index) in usedViews" :key="index">
            <tr
              class="p-2 flex items-center justify-between bg-gray-200 border-b"
            >
              <td
                @click="toggleView(usedView, index)"
                role="button"
                class="text-sm font-light"
              >
                <div class="flex items-center">
                  <div v-if="hasViews(usedView.views)" class="mr-4">
                    <svg
                      v-if="!usedView.showViews"
                      role="button"
                      class="h-4 w-4 fill-current text-black"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                      />
                    </svg>
                    <svg
                      v-else
                      role="button"
                      class="h-4 w-4 fill-current text-black"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M10.707 7.05L10 6.343 4.343 12l1.414 1.414L10 9.172l4.243 4.242L15.657 12z"
                      />
                    </svg>
                  </div>
                  <span>
                    {{ usedView.route.uri }}
                  </span>
                </div>
              </td>
              <td>
                <div class="flex items-center">
                  <div class="px-2">
                    <button
                      @click="showModal(usedView)"
                      class="border border-gray-600 rounded px-2 text-sm text-gray-600"
                    >
                      Details
                    </button>
                  </div>
                </div>
              </td>
            </tr>
            <tr
              v-show="usedView.showViews"
              v-for="(count, view) in usedView.views"
              :key="view"
              class="px-4 py-2 flex justify-between items-center bg-gray-100"
            >
              <td class="text-gray-800 text-sm font-light">{{ view }}</td>
              <td>
                <div class="flex items-center">
                  <div
                    class="flex items-center justify-center rounded shadow px-2 bg-gray-600 shadow mr-4"
                  >
                    <span class="text-white text-xs"
                      >Used {{ count }} times</span
                    >
                  </div>
                  <div>
                    <button
                      @click="deleteView(view, index)"
                      class="flex items-center justify-center"
                    >
                      <svg
                        class="h-5 w-5 fill-current text-gray-500 hover:text-gray-700"
                        viewBox="0 0 1792 1792"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M704 736v576q0 14-9 23t-23 9h-64q-14 0-23-9t-9-23v-576q0-14 9-23t23-9h64q14 0 23 9t9 23zm256 0v576q0 14-9 23t-23 9h-64q-14 0-23-9t-9-23v-576q0-14 9-23t23-9h64q14 0 23 9t9 23zm256 0v576q0 14-9 23t-23 9h-64q-14 0-23-9t-9-23v-576q0-14 9-23t23-9h64q14 0 23 9t9 23zm128 724v-948h-896v948q0 22 7 40.5t14.5 27 10.5 8.5h832q3 0 10.5-8.5t14.5-27 7-40.5zm-672-1076h448l-48-117q-7-9-17-11h-317q-10 2-17 11zm928 32v64q0 14-9 23t-23 9h-96v948q0 83-47 143.5t-113 60.5h-832q-66 0-113-58.5t-47-141.5v-952h-96q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h309l70-167q15-37 54-63t79-26h320q40 0 79 26t54 63l70 167h309q14 0 23 9t9 23z"
                        />
                      </svg>
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="py-10"></div>
    </div>

    <div
      v-if="currentUsedView"
      class="fixed inset-0 z-50 overflow-auto bg-smoke-light flex py-10 px-4 sm:px-0"
    >
      <div
        class="relative p-8 bg-gray-200 w-full max-w-3xl m-auto flex-col flex rounded"
      >
        <div class="absolute right-0 mr-4 -mt-2">
          <svg
            @click="hideModal"
            role="button"
            class="fill-current text-gray-500 hover:text-gray-800 h-4 w-4"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
          >
            <title>close</title>
            <path
              d="M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z"
            />
          </svg>
        </div>
        <div class="w-full">
          <div class="rounded shadow break-words border mt-5 mb-4 p-4">
            <div class="mb-2">
              <h3 class="text-black font-semibold text-sm">Route Details :</h3>
            </div>

            <vue-json-pretty :data="currentUsedView.route"></vue-json-pretty>
          </div>

          <div class="rounded shadow border mt-5 mb-4 p-4">
            <div class="mb-2">
              <h3 class="text-black font-semibold text-sm">View Structure :</h3>
            </div>

            <vue-json-pretty :data="currentUsedView.parent"></vue-json-pretty>
          </div>

          <div class="rounded shadow border mt-5 mb-4 p-4">
            <div class="mb-2">
              <h3 class="text-black font-semibold text-sm">Action Content :</h3>
            </div>

            <prism language="php" :code="currentUsedView.action"></prism>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueJsonPretty from 'vue-json-pretty';
import 'prismjs/prism';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-php';
import 'prismjs/themes/prism-okaidia.css';
import Prism from 'vue-prism-component';
import { debounce } from 'lodash';
import DeleteModalMixin from '../mixins/delete-modal';

export default {
  components: {
    VueJsonPretty,
    Prism
  },

  mixins: [DeleteModalMixin],

  data() {
    return {
      usedViewsOriginal: null,
      usedViews: null,
      currentUsedView: null,
      search: null
    };
  },

  mounted() {
    this.usedViewsOriginal = this.$attrs.usedviews;

    this.usedViews = this.$attrs.usedviews;
  },

  watch: {
    search(val) {
      this.searching(val);
    },
    usedViewsOriginal() {
      this.unusedViews = this.usedViewsOriginal;
    }
  },

  methods: {
    showModal(currentView) {
      this.currentUsedView = currentView;
    },

    hideModal(currentView) {
      this.currentUsedView = null;
    },

    toggleView(usedView, index) {
      if (!usedView.showViews) {
        this.showViews(index);
        return;
      }

      this.hideViews(index);
    },

    showViews(index) {
      this.$set(
        this.usedViews,
        index,
        Object.assign(this.usedViews[index], {
          showViews: true
        })
      );
    },

    hideViews(index) {
      this.$set(
        this.usedViews,
        index,
        Object.assign(this.usedViews[index], {
          showViews: false
        })
      );
    },

    hasViews(views) {
      if (views instanceof Array) {
        return false;
      }

      if (views instanceof Object) {
        return true;
      }

      return false;
    },

    deleteView(view, index) {
      this.deleteModal(view, () => {
        this.$delete(this.usedViewsOriginal[index].views, view);
      });
    },

    searching: debounce(function(keyword) {
      this.usedViews = this.usedViewsOriginal.filter(view => {
        const regex = new RegExp(keyword, 'gi');
        return view.route.uri.match(regex);
      });
    }, 200)
  }
};
</script>
