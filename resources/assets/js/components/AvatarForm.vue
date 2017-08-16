<template>
	<div>
		<h1>
			{{ profileUser.name }}
			<small>joined us {{ joined }}</small>
		</h1>
		<form v-if="canUpdateAvatar" enctype="multipart/form-data" method="POST">
			<div class="form-group">
				<input type="file" name="avatar" accept="image/*" @change="onChange">
			</div>
		</form>

		<img :src="avatar" :alt="altText" width="75" height="75" class="br-5">
	</div>
</template>

<script>
	import moment from 'moment';
	export default {
		props: ['profileUser'],
		data() {
			return {
				avatar: this.profileUser.avatar_path
			}
		},
		methods: {
			onChange(e) {
				if (!e.target.files.length) return;

				let avatar = e.target.files[0];
				let reader = new FileReader();

				reader.readAsDataURL(avatar);

				reader.onload = e => {
					this.avatar = e.target.result;
				};

				// Persist to server
				this.persist(avatar);
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
			joined() {
				return moment(this.profileUser.created_at).parseZone('UTC').fromNow();
			}
		}
	}
</script>