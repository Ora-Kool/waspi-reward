<script setup>
import CommentForm from "@/Components/Form/CommentForm.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PostForm from "@/Components/Form/PostForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PostList from "@/Components/Post/PostList.vue";
import EmptyBox from "@/Components/Icons/EmptyBox.vue";
import { ref, computed } from "vue";

const props = defineProps({
  posts: Object,
  comments: Object,
});

const isPosts = computed(() => (Object.keys(props.posts).length > 0 ? true : false));

const openCommentModal = ref(false);

const postTitle = ref("");

const form = useForm({
  comment: "",
});

const openingModal = (param) => {
  postTitle.value = param;
  openCommentModal.value = true;
};

const submit = () => {
  form.post(route("comment.store", postTitle.value), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  openCommentModal.value = false;

  form.reset();
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="px-6 py-4 text-2xl text-center text-gray-900 underline">
            Create a post
          </div>

          <PostForm />
        </div>
      </div>
    </div>

    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <template v-if="isPosts">
              <ol class="relative border-l border-gray-200 dark:border-gray-700">
                <PostList
                  @open-modal="openingModal"
                  v-for="(post, index) in props.posts"
                  :key="index"
                  :posts="post"
                  :badges="post.badges"
                />
              </ol>
            </template>

            <template v-else>
              <div class="grid place-content-center">
                <EmptyBox />

                <span>No Posts</span>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>

    <Modal :show="openCommentModal" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          Comments for <span class="text-red-500">{{ postTitle }}</span>
        </h2>

        <div class="grid mt-6 md:grid-cols-2">
          <div>
            <InputLabel for="comment" value="Comment" class="sr-only" />

            <TextInput
              id="comment"
              ref="commentInput"
              v-model="form.comment"
              type="text"
              class="block w-full mt-1"
              placeholder="Enter a comment"
              @keyup.enter="submit"
            />

            <InputError :message="form.errors.comment" class="mt-2" />
          </div>

          <div class="flex justify-end">
            <PrimaryButton
              class="ml-3"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
              @click="submit"
            >
              Post
            </PrimaryButton>
          </div>
        </div>
      </div>

      <CommentForm :comments="comments" />
    </Modal>
  </AuthenticatedLayout>
</template>
