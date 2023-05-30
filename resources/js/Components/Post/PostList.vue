<script setup>
import SuperBadge from "@/Components/Badge/SuperBadge.vue";
import TopBadge from "@/Components/Badge/TopBadge.vue";
import BeginnerBadge from "@/Components/Badge/BeginnerBadge.vue";
import Comment from "@/Components/Icons/Comment.vue";
import Like from "@/Components/Icons/Like.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  posts: Object,
  badges: Array,
});

defineEmits(["openModal"]);

const form = useForm({});

const submit = () => {
  form.get(route("post.like", props.posts.title), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <li class="mb-10 ml-6">
    <span
      class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900"
    >
      <svg
        aria-hidden="true"
        class="w-3 h-3 text-blue-800 dark:text-blue-300"
        fill="currentColor"
        viewBox="0 0 20 20"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          fill-rule="evenodd"
          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
          clip-rule="evenodd"
        ></path>
      </svg>
    </span>
    <h3
      class="flex items-center mb-1 text-lg font-semibold text-gray-900 capitalize dark:text-white"
    >
      {{ props.posts.title }}
      <div class="flex">
        <span
          class="bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 ml-3"
          v-text="props.posts.user"
        ></span>
        <div v-for="badge in props.badges" class="flex items-center gap-1">
          <BeginnerBadge v-show="badge.id === 1" />

          <TopBadge v-show="badge.id === 2" />

          <SuperBadge v-show="badge.id === 3" />
        </div>
      </div>
    </h3>
    <time
      class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500"
      >Released on {{ props.posts.date }}</time
    >
    <p
      class="text-base font-normal text-gray-500 dark:text-gray-400"
      v-text="props.posts.message"
    ></p>
    <div
      @click="$emit('openModal', props.posts.title)"
      class="inline-flex items-center gap-1 text-sm cursor-pointer"
    >
      <Comment />
      <span class="font-bold" v-text="props.posts.comments"></span>
    </div>

    <div
      @click="submit"
      class="inline-flex items-center gap-1 ml-2 text-sm cursor-pointer"
    >
      <Like :class="{ 'fill-sky-500': props.posts.isLiked }" />
      <span class="font-bold" v-text="props.posts.likes"></span>
    </div>
  </li>
</template>
