<template>
	<div>
		<div class="checkbox checkbox-info">
			<input :id="`customCheckbox_${id}`" type="checkbox" v-model="checked">
			<label :for="`customCheckbox_${id}`">{{label}}</label>
			<span></span>
		</div>
	</div>
</template>
<script>
export default {
	name: 'Checkbox',
	props: {
		id: {
			type: [String, Number],
			default: 0
		},
		label: {
			type: [String],
			default: ''
		},
		isChecked: {
			type: Boolean,
			default: false
		},
		params: {
			type: [String, Number, Object]
		}
	},
	computed: {
		checked: {
			set(val) {
				this.$emit('checked', val, this.params)
			},
			get() {
				return this.isChecked
			}
		}
	}
}
</script>

