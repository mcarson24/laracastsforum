<template>
	<div>
		<div class="level">
			<img :src="avatar" :alt="altText" width="75" height="75" class="mr-1 mb-1 br-5">
			<h1>
				{{ profileUser.name }}
				<small>joined us {{ memberSince }}</small>
		 	</h1>
		</div>
		<form v-if="canUpdateAvatar" enctype="multipart/form-data" method="POST">
			<div class="form-group">
				<image-upload name="avatar" @loaded="onLoad"></image-upload>
			</div>
		</form>
	</div>
</template>

<script>
	import moment from 'moment';
	import ImageUpload from './ImageUpload';
	export default {
		components: { ImageUpload },
		props: ['profileUser'],
		data() {
			return {
				avatar: this.profileUser.avatar_path
			}
		},
		methods: {
			onLoad(avatar) {
				this.avatar = avatar.src;

				// Persist to server
				this.persist(avatar.file);
			},
			persist(avatar) {
				let data = new FormData();
				data.append('avatar', avatar);

				axios.post(`/api/users/${this.profileUser.id}/avatar`, data)
					 .then(() => flash('Avatar saved!'));
			}
		},
		computed: {
			canUpdateAvatar() {
				return this.authorize(user => user.id === this.profileUser.id);
			},
			altText() {
				return `${this.profileUser.name}'s avatar`;
			},
			memberSince() {
				return moment(this.profileUser.created_at).parseZone('UTC').fromNow();
			}
		}
	}
</script>