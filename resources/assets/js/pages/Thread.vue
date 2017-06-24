<script>
	import Replies from '../components/Replies.vue';

	export default {
		components: { Replies },
		props: ['intialRepliesCount'],
		data() {
			return {
				repliesCount: this.intialRepliesCount
			}
		}
	}
</script>