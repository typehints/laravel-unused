<template>
  <div class="container mx-auto pt-10">
		<div class="bg-yellow-100 border-l-4 border-yellow-500 px-4 py-2 mb-10 shadow rounded-r">
			<p class="traking-wide text-yellow-800 text-sm leading-loose">
				Currently, the package scans the available routes and returns a list of both the <strong>used</strong> and the <strong>unused</strong> views.<br>
				Meaning, the views that are not being displayed on browser and are only used for emails or generating PDFs will be considered as <strong>unused views</strong>.
			</p>
		</div>

		<div class="flex">
			<div class="hidden sm:block w-1/5 mr-4">
				<nav>
					<div>
						<h3
							class="px-3 text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider"
						>
							Views
						</h3>
						<div class="mt-1">
							<a
								href="#"
								v-for="(option, index) in options"
								:key="index"
								@click="isActive(option)"
								class="group flex items-center px-3 py-2 text-sm leading-5 font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150"
								:class="{
									'text-gray-900 bg-gray-100':
										option.component === currentComponent
								}"
							>
								<div class="flex items-center">
									<span class="truncate">
										{{ option.label }}
									</span>
									<span
										class="ml-3 inline-flex items-center rounded-full px-1 rounded-full text-xs font-medium leading-4 bg-gray-600 shadow text-white"
									>
										{{ $attrs[option.attribute].length }}
									</span>
								</div>
							</a>
						</div>
					</div>
				</nav>
			</div>

			<div class="px-2 sm:px-0 flex-1">
				<keep-alive>
					<component
						:is="currentComponent"
						:usedviews="$attrs.usedviews"
						:unusedviews="$attrs.unusedviews"
					></component>
				</keep-alive>
			</div>
		</div>
  </div>
</template>

<script>
import UsedViews from '../components/UsedViews';
import UnusedViews from '../components/UnusedViews';

export default {
  components: {
    UsedViews,
    UnusedViews
  },

  data() {
    return {
      options: [
        {
          label: 'Unused',
          component: 'UnusedViews',
          attribute: 'unusedviews'
        },
        {
          label: 'Used',
          component: 'UsedViews',
          attribute: 'usedviews'
        }
      ],
      currentComponent: 'UnusedViews'
    };
  },

  methods: {
    isActive(option) {
      this.currentComponent = option.component;
    }
  }
};
</script>

<style>
.fixed-height {
  height: 70vh;
}
</style>
