<template>
	<div class="panel reply-panel" :class="isBest ? 'panel-success' : ''">
		<div :id="'reply-' + this.id" class="panel-heading">
			<div v-if="editing">
				<form @submit="update">
					<div class="form-group">
						<textarea class="form-control" v-model="body" required></textarea>
					</div>
					<button class="btn btn-default btn-xs" type="button" @click="editing = false">Cancel</button>
					<button class="btn btn-default btn-xs">Update</button>
				</form>
			</div>
			<div v-else v-html="body"></div>
		</div>
		<div class="panel-body">
			<div class="level">
				<h5 class="flex">
				    by <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a>
				    <span class="reply-time" v-text="ago"></span>
				</h5>
					<div v-if="signedIn">
				    	<favorite :reply="data"></favorite>
			    	</div>
			    	<div v-else>
				    	<span class="glyphicon glyphicon-heart mr-quarter"></span>
			    		<span>{{ data.favoritesCount }}</span>
		    		</div>
		    </div>
		</div>
			<div class="panel-footer level" v-if="authorize('owns', reply.thread) || authorize('owns', reply)">
				<div v-if="authorize('owns', reply)">
					<button class="btn btn-default btn-xs mr-1" @click="editing = !editing">Edit</button>
					<button class="btn btn-default btn-xs" @click="destroy">Delete</button>
				</div>
				<button class="btn btn-default btn-xs m-l-auto" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply</button>
			</div>
	</div>
</template>

<script>
	import Favorite from './Favorite.vue';
	import moment from 'moment';

	export default {
		props: ['data'],
		components: { Favorite },
		data() {
			return {
				editing: false,
				body: this.data.body,
				id: this.data.id,
				isBest: this.data.isBest,
				reply: this.data
			};
		},
		computed: {
			ago() {
				return moment(this.data.created_at).parseZone('UTC').fromNow();
			}
		},
		created() {
			window.events.$on('best-reply-selected', id => {
				this.isBest = (id === this.reply.id);
			});
		},
		methods: {
			update() {
				axios.patch('/replies/' + this.data.id, {
					body: this.body
				}).catch(errors => {
					flash(errors.response.data, 'danger');
				});
				flash('Updated your reply!');
				this.editing = false;
			},
			destroy() {
				axios.delete('/replies/' + this.data.id);

				this.$emit('deleted', this.id);

				flash('Your reply was deleted.');
			},
			markBestReply() {
				axios.post(`/replies/${this.reply.id}/best`);

				window.events.$emit('best-reply-selected', this.reply.id);
			}
		}
	}
</script>
