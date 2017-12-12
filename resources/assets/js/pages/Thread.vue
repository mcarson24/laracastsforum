<script>
	import Replies from '../components/Replies.vue';
	import SubscribeButton from '../components/SubscribeButton.vue';

	export default {
		components: { Replies, SubscribeButton },
		props: ['thread'],
		data() {
			return {
				repliesCount: this.thread.replies_count,
				locked: this.thread.locked
			}
		},
		methods: {
			toggleLock() {
				this.locked = !this.locked;

				if (this.locked) {
					axios.post(`/lock-thread/${this.thread.slug}`);
				}
				else {
					axios.delete(`/unlock-thread/${this.thread.slug}`);
				}
			}
		},
	}
</script>
