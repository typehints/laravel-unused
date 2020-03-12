<template>
  <div class="w-full">
    <div class="mb-4">
      <div>
        <label
          for="email"
          class="block text-sm font-medium leading-5 text-gray-700"
          >Search By View</label
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
            placeholder="post.index"
          />
        </div>
      </div>
    </div>

    <div class="fixed-height overflow-auto">
      <table class="table-auto w-full rounded shadow">
        <tbody v-for="(unusedView, index) in unusedViews" :key="index">
          <tr
            class="p-2 flex items-center justify-between bg-gray-200 border-b"
          >
            <td class="text-sm font-light w-full">
              <div class="flex justify-between items-center">
                <span>
                  {{ unusedView }}
                </span>
                <div class="flex">
                  <button
                    @click="deleteView(unusedView)"
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
  </div>
</template>

<script>
import { debounce } from 'lodash';
import DeleteModalMixin from '../mixins/delete-modal';

export default {
  data() {
    return {
      unusedViewsOriginal: null,
      unusedViews: null,
      currentUsedView: null,
      search: null
    };
  },

  mixins: [DeleteModalMixin],

  mounted() {
    this.unusedViewsOriginal = this.$attrs.unusedviews;

    this.unusedViews = this.$attrs.unusedviews;
  },

  watch: {
    search(val) {
      this.searching(val);
    },
    unusedViewsOriginal() {
      this.unusedViews = this.unusedViewsOriginal;
    }
  },

  methods: {
    searching: debounce(function(keyword) {
      this.unusedViews = this.unusedViewsOriginal.filter(view => {
        const regex = new RegExp(keyword, 'gi');
        return view.match(regex);
      });
    }, 200),

    deleteView(view) {
      this.deleteModal(view, () => {
        this.unusedViewsOriginal = this.unusedViewsOriginal.filter(
          unusedView => {
            return unusedView !== view;
          }
        );
      });
    }
  }
};
</script>
