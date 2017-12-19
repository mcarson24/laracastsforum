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
				editing: false,
				form: {
					title: this.thread.title,
					body: this.thread.body
				}
			}
		},
		methods: {
			toggleLock() {
				this.locked = !this.locked;

				axios[this.locked ? 'post' : 'delete'](`/lock-thread/${this.thread.slug}`);
			},
			toggleEdit() {
				this.editing = !this.editing;
			},
			update() {
				axios.patch(`/threads/${this.thread.channel.slug}/${this.thread.slug}`, this.form)
					 .then(() => {
						flash('Your thread post has been updated.');
						this.toggleEdit();
				});
			},
			cancel() {
				this.toggleEdit();
				this.form.title = this.thread.title,
				this.form.body = this.thread.body
			}
		}
	}
</script>
