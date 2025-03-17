<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
        default: () => []
    },
    options: {
        type: Array,
        required: true,
        default: () => []
    }
});

const emit = defineEmits(['update:modelValue']);

const checkboxGroup = ref([]);

onMounted(() => {
    const autofocusOption = checkboxGroup.value.find(checkbox => checkbox.hasAttribute('autofocus'));
    if (autofocusOption) {
        autofocusOption.focus();
    }
});

defineExpose({
    focus: (value) => {
        const checkbox = checkboxGroup.value.find(checkbox => checkbox.value === value);
        if (checkbox) {
            checkbox.focus();
        }
    }
});

const updateValue = (value) => {
    const newValue = [...props.modelValue];
    const index = newValue.indexOf(value);
    if (index === -1) {
        newValue.push(value);
    } else {
        newValue.splice(index, 1);
    }
    emit('update:modelValue', newValue);
};
</script>

<template>
    <div>
        <label
            v-for="option in options"
            :key="option.value"
            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
        >
            <input
                ref="checkboxGroup"
                type="checkbox"
                :value="option.value"
                :checked="props.modelValue.includes(option.value)"
                @change="updateValue(option.value)"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                name="checkbox-group"
            >
            <span class="ml-2">{{ option.label }}</span>
        </label>
    </div>
</template>