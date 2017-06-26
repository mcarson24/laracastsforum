<template>
	<nav aria-label="Page navigation" class="text-center" v-show="shouldBePaginated">
	  <ul class="pagination">
	    <li v-show="prevUrl">
	      <a href="#" aria-label="Previous" rel="prev" @click.prevent="currentPage--">
	        <span aria-hidden="true">&laquo; Previous</span>
	      </a>
	    </li>
	    <li v-show="nextUrl">
	      <a href="#" aria-label="Next" rel="next" @click.prevent="currentPage++">
	        <span aria-hidden="true">Next &raquo;</span>
	      </a>
	    </li>
	  </ul>
	</nav>
</template>

<script>
	export default {
		props: ['data'],
		data() {
			return {
				prevUrl: false,
				nextUrl: false,
				currentPage: 1
			}
		},
		watch: {
			data() {
				this.currentPage = this.data.current_page;
				this.prevUrl = this.data.prev_page_url;
				this.nextUrl = this.data.next_page_url;
			},
			currentPage() {
				this.broadcast().updateUrl();
			}
		},
		computed: {
			shouldBePaginated() {
				return !!this.prevUrl || !!this.nextUrl;
			}
		},
		methods: {
			broadcast() {
				return this.$emit('changed-page', this.currentPage);
			},
			updateUrl() {
				history.pushState(null, null, `?page=${this.currentPage}`);
			}

		}
	}
</script> 