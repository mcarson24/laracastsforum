<script>
	import Replies from '../components/Replies.vue';
	import SubscribeButton from '../components/SubscribeButton.vue';

	export default {
		components: { Replies, SubscribeButton },
		props: ['thread'],
		data() {
			return {
				repliesCount: this.thread.replies_count,
				locked: this.thread.locked,
				editing: false
			}
		},
		methods: {
			toggleLock() {
				this.locked = !this.locked;

				axios[this.locked ? 'post' : 'delete'](`/lock-thread/${this.thread.slug}`);
			},
			toggleEdit() {
				this.editing = !this.editing;
			}
		}
	}
</script>
