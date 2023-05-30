<script setup>
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
  comments: Object,
});

// const form = useForm();

const deleted = (param) => {
  router.delete(route("comment.delete", param));
};
</script>

<template>
  <ol class="relative mx-5 border-l border-gray-200 dark:border-gray-700">
    <li v-for="comment in props.comments" class="my-3 ml-6">
      <span
        class="absolute flex items-center justify-center bg-blue-100 rounded-full w-7 h-7 -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900"
      >
        <img
          class="object-cover object-center rounded-full shadow-lg"
          src="http://waspito.test/images/curly-hair-man.png"
          alt="Thomas Lean image"
        />
      </span>
      <div
        class="relative p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600"
      >
        <div
          @click="deleted(comment.id)"
          class="absolute top-0 right-0 flex w-6 translate-x-2 -translate-y-3 bg-red-300 rounded-full cursor-pointer place-content-center"
        >
          <span class="text-red-500">&cross;</span>
        </div>

        <div class="items-center justify-between mb-3 sm:flex">
          <time
            class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0"
            v-text="comment.createdAt"
          ></time>

          <div class="text-sm font-normal text-gray-500 lex dark:text-gray-300">
            <span v-text="comment.user" class="text-red-500"></span> commented
          </div>
        </div>
        <div
          class="p-3 text-xs italic font-normal text-gray-500 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300"
          v-text="comment.message"
        ></div>
      </div>
    </li>
  </ol>
</template>
