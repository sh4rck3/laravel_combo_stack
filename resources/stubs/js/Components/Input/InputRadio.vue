<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: [String, Number],  // Dependendo do tipo de valor que você espera do radio
    options: {
        type: Array,
        required: true,
        default: () => []
    },
    name: {
        type: String,
        default: 'radio-group'
    }
});

const emit = defineEmits(['update:modelValue']);

const radioGroup = ref([]);

onMounted(() => {
    const autofocusOption = radioGroup.value.find(radio => radio.hasAttribute('autofocus'));
    if (autofocusOption) {
        autofocusOption.focus();
    }
});

defineExpose({
    focus: (value) => {
        const radio = radioGroup.value.find(radio => radio.value === value);
        if (radio) {
            radio.focus();
        }
    }
});
</script>

<template>
    <div>
        <label
            v-for="option in options"
            :key="option.value"
            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
        >
            <input
                ref="radioGroup"
                type="radio"
                :value="option.value"
                :checked="option.value === modelValue"
                @change="$emit('update:modelValue', option.value)"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                :name="name"
            >
            <span class="ml-2">{{ option.label }}</span>
        </label>
    </div>
    <div></div>
</template>