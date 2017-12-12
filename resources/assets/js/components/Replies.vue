<template>
	<div>
		<div v-for="(reply, index) in items">
			<reply :reply="reply" @deleted="remove(index)" :key="reply.id"></reply>
		</div>
        <paginator :data="dataSet" @changed-page="fetch"></paginator>
        <p v-if="$parent.locked" class="text-center alert alert-danger m-all-small">
        	This thread has been locked by an administrator. No new replies are being accepted.
        </p>
        <new-reply @created="add" v-else></new-reply>
	</div>
</template>

<script>
	import Reply from './Reply.vue';
	import NewReply from './NewReply.vue';
	import collection from '../mixins/collection.js';

	export default {
		components: { Reply, NewReply },
		mixins: [collection],
		data() {
			return {
				dataSet: false,
				locked: false
			}
		},
		created() {
			this.fetch();
		},
		methods: {
			fetch(page) {
				axios.get(this.url(page))
					 .then(this.refresh);
			},
			refresh({data}) {
				this.dataSet = data;
				this.items = data.data;

				window.scrollTo(0, 0);
			},
			url(page) {
				if (!page) {
					let query = location.search.match(/page=(\d+)/);
					page = query ? query[1] : 1;
				}
				return `${location.pathname}/replies?page=${page}`;
			}
			
		}
	}
</script>
