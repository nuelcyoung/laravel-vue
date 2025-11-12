<script setup>
import MagnifyingGlass from '@/Components/Icons/MagnifyingGlass.vue';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link,Head,usePage,useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

defineProps({
  students: {
    type: Object,
    required: true
  },
  classes: {
    type: Object,
    required: true
  },
});
let pageNumber = ref(1),
    searchTerm = ref(usePage().props.search ?? ""),
    class_id = ref(usePage().props.class_id ?? "");

const pageNumberUpdated = (link) => {
    pageNumber.value = link.url.split("=")[1];
};

let studentsUrl = computed(() => {
    const url = new URL(route("students.index"));

    url.searchParams.set("page", pageNumber.value);

    if (searchTerm.value) {
        url.searchParams.set("search", searchTerm.value);
    }

    if (class_id.value) {
        url.searchParams.append("class_id", class_id.value);
    }

    return url;
});

watch(
    () => studentsUrl.value,
    (newValue) => {
        router.visit(newValue, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    }
);

watch(
    () => searchTerm.value,
    (value) => {
        if (value) {
            pageNumber.value = 1;
        }
    }
);

const deleteForm = useForm({});

const deleteStudent = (id) => {
    if (confirm("Are you sure you want to delete this student?")) {
        deleteForm.delete(route("students.destroy", id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>

  <Head title="Students" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Students
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="px-4 sm:px-6 lg:px-8">
              <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                  <h1 class="text-xl font-semibold text-gray-900">
                    Students
                  </h1>
                  <p class="mt-2 text-sm text-gray-700">
                    A list of all the Students.
                  </p>
                </div>

                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                  <Link :href="route('students.create')"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                  Add Student
                  </Link>
                </div>
              </div>

              <div class="flex flex-col justify-start sm:flex-row mt-6">
                <div class="relative text-sm text-gray-800 col-span-3">
                  <div class="absolute pl-2 left-0 top-0 bottom-0 flex items-center pointer-events-none text-gray-500">
                    <MagnifyingGlass />
                  </div>

                  <input type="text" v-model="searchTerm" placeholder="Search students data..." id="search"
                    class="block rounded-lg border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>

                <select v-model="class_id"
                  class="block rounded-lg border-0 py-2 ml-5 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 sm:text-sm sm:leading-6">
                  <option value="">Filter By Class</option>
                  <option :value="item.id" :key="item.id" v-for="item in classes.data">
                    {{ item.name }}
                  </option>
                </select>
              </div>
              <table class="min-w-full bg-white divided-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="py-2 px-4 border-b text-left"> ID</th>
                    <th class="py-2 px-4 border-b text-left">Name</th>
                    <th class="py-2 px-4 border-b text-left">Email</th>
                    <th class="py-2 px-4 border-b text-left">Class</th>
                    <th class="py-2 px-4 border-b text-left">Section</th>
                    <th class="py-2 px-4 border-b text-left">Created At</th>
                    <th class="py-2 px-4 border-b text-left">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="student in students.data" :key="student.id" class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ student.id }}</td>
                    <td class="py-2 px-4 border-b">{{ student.name }}</td>
                    <td class="py-2 px-4 border-b">{{ student.email }}</td>
                    <td class="py-2 px-4 border-b">{{ student.class?.name || 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ student.section?.name || 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ student.created_at }}</td>
                    <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                                >
                                                    <Link
                                                        :href="
                                                            route(
                                                                'students.edit',
                                                                student.id
                                                            )
                                                        "
                                                        class="text-indigo-600 hover:text-indigo-900"
                                                    >
                                                        Edit
                                                    </Link>
                                                    <button
                                                        @click="
                                                            deleteStudent(
                                                                student.id
                                                            )
                                                        "class="ml-2 text-red-600 hover:text-red">
                                                        Delete
                                                    </button>
                                                </td>
                  </tr>
                </tbody>
              </table>

              <!-- Pagination -->
              <Pagination :links="students.meta.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>