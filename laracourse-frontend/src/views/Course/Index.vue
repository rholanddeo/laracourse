<template>
  <div class="container pt-20 pb-10">
    <div class="grid gap-8 grid-cols-12">
      <div class="lg:col-start-1 lg:col-end-9 col-span-12 order-2 lg:order-1">
        <!-- Section: Show course -->
        <div class="border rounded-lg bg-white overflow-hidden mb-7">
          <div class="border-b p-4">
            <p class="text-lg font-semibold">Data course:</p>
          </div>
          <div class="p-4">
            <form @submit.prevent="getCourses">
              <div class="grid lg:grid-cols-12 gap-3 lg:gap-4">
                <div class="lg:col-span-auto">
                  <select
                    v-model="filter.ordering"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                  >
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                  </select>
                </div>
                <div class="lg:col-span-3">
                  <select
                    v-model="filter.category"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                  >
                    <option value="">All Category</option>
                    <template
                      v-for="category in state.categories"
                      :key="category.id"
                    >
                      <option :value="category.slug">
                        {{ category.name }}
                      </option>
                    </template>
                  </select>
                </div>
                <div class="lg:col-end-13 lg:col-span-4">
                  <div class="relative">
                    <div
                      class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                    >
                      <svg
                        aria-hidden="true"
                        class="w-4 h-4 text-gray-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        ></path>
                      </svg>
                    </div>
                    <input
                      v-model="filter.search"
                      class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Search Course..."
                      required
                    />
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
              <thead
                class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-t"
              >
                <tr>
                  <th scope="col" class="px-6 py-3 w-2">No</th>
                  <th scope="col" class="px-6 py-3">Code</th>
                  <th scope="col" class="px-6 py-3">Name</th>
                  <th scope="col" class="px-6 py-3">Duration</th>
                  <th scope="col" class="px-6 py-3">Category</th>
                  <th scope="col" class="px-6 py-3">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  class="bg-white border-t"
                  v-for="(course, index) in state.courses.data"
                  :key="course.id"
                >
                  <td class="px-6 py-4">{{ index + 1 }}.</td>
                  <th
                    scope="row"
                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                  >
                    {{ course.code }}
                  </th>
                  <td class="px-6 py-4">
                    {{ course.name }}
                  </td>
                  <td class="px-6 py-4">
                    {{ course.duration }}
                  </td>
                  <td class="px-6 py-4">
                    {{ course.category.name }}
                  </td>
                  <td class="px-6 py-4 flex gap-3">
                    <router-link
                      :to="`/course/${course.id}/edit`"
                      class="font-medium text-green-600 hover:underline"
                      >Edit</router-link
                    >
                    <a
                      href="#!"
                      @click.prevent="deleteCourse(course.id)"
                      class="font-medium text-red-600 hover:underline"
                      >Delete</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div
          class="lg:flex grid gap-5 lg:gap-0 justify-center lg:justify-between lg:items-center text-center"
        >
          <div class="text-sm text-gray-700">
            Showing
            <span class="font-semibold">{{ state.courses.current_page }}</span>
            to
            <span class="font-semibold">{{ state.courses.per_page }}</span> of
            <span class="font-semibold">{{ state.courses.total }}</span> Entries
          </div>

          <nav aria-label="">
            <ul class="inline-flex -space-x-px text-sm gap-2">
              <template v-for="link in state.courses.links" :key="link.label">
                <li>
                  <a
                    href="javascript:;"
                    @click="pagination(link.url)"
                    class="px-3 py-2 ml-0 leading-tight border rounded-lg"
                    :class="
                      link.active
                        ? 'bg-blue-600 border-blue-600 text-white'
                        : 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700'
                    "
                  >
                    {{ link.label }}
                  </a>
                </li>
              </template>
            </ul>
          </nav>
        </div>
      </div>

      <div class="lg:col-start-9 lg:col-end-13 col-span-12 order-1 lg:order-2">
        <!-- Section: Input course -->
        <div class="border rounded-lg bg-white">
          <div class="border-b p-4">
            <p class="text-lg font-semibold">Form Input Course:</p>
          </div>
          <div class="p-4">
            <form @submit.prevent="createCourse">
              <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900"
                  >Course Code</label
                >
                <input
                  type="text"
                  v-model="form.code"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                />
                <p
                  class="mt-2 text-sm text-red-600"
                  v-if="state.errors['code']"
                >
                  {{ state.errors["code"][0] }}
                </p>
              </div>
              <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900"
                  >Course Name</label
                >
                <input
                  type="text"
                  v-model="form.name"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                />
                <p
                  class="mt-2 text-sm text-red-600"
                  v-if="state.errors['name']"
                >
                  {{ state.errors["name"][0] }}
                </p>
              </div>
              <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900"
                  >Duration</label
                >
                <input
                  type="number"
                  v-model="form.duration"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                />
                <p
                  class="mt-2 text-sm text-red-600"
                  v-if="state.errors['duration']"
                >
                  {{ state.errors["duration"][0] }}
                </p>
              </div>

              <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900"
                  >Description</label
                >
                <textarea
                  v-model="form.description"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                  rows="4"
                ></textarea>
                <p
                  class="mt-2 text-sm text-red-600"
                  v-if="state.errors['description']"
                >
                  {{ state.errors["description"][0] }}
                </p>
              </div>

              <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900"
                  >Category</label
                >
                <select
                  v-model="form.category_id"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                >
                  <option value="">-Choose a category-</option>
                  <template
                    v-for="category in state.categories"
                    :key="category.id"
                  >
                    <option :value="category.id">{{ category.name }}</option>
                  </template>
                </select>
                <p
                  class="mt-2 text-sm text-red-600"
                  v-if="state.errors['category_id']"
                >
                  {{ state.errors["category_id"][0] }}
                </p>
              </div>
              <button
                type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 w-full"
              >
                Save
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { onMounted, ref, reactive, watch } from "vue";
import { useToast } from "vue-toastification";

export default {
  setup() {
    // define state
    const state = ref({
      categories: [],
      courses: [],
      errors: [],
    });
    const toast = useToast();


    // panggil data categories dari api
    const getCategories = async () => {
      let { data } = await axios.get("/api/categories");

      // data response dikirim ke state categories
      state.value.categories = data;
    };

    // panggil data courses dari api
    const getCourses = async () => {
      let { data } = await axios.get("/api/courses", {
        params: filter,
      });

      // data response dikirim ke state courses
      state.value.courses = data;
    };

    onMounted(() => {
      // load category
      getCategories();
      // load course
      getCourses();
    });

    // define variable form untuk menampung value dari setiap inputan
    const form = reactive({
      // jangan lupa import reactive pada vue
      code: "",
      name: "",
      duration: "",
      description: "",
      category_id: "",
    });

    // fungsi create course
    const createCourse = async () => {
      try {
        // panggil api untuk input course dan sertakan variabel form di dalam nya
        await axios.post("/api/courses", form);

        // Jika proses berhasil maka panggil getCourses() untuk mendapatkan data terbaru
        getCourses();
        toast.success("Course Added Succesfully");
      } catch (error) {
        // jika gagal, masukan response error pada state errors
        state.value.errors = error.response.data.errors;
        toast.error("Course Added Failed");
      }
    };

    // fungsi delete course
    const deleteCourse = async (id) => {
      // panggil api untuk input course dan sertakan variabel id
      await axios.delete(`/api/courses/${id}`);

      // Jika proses berhasil maka panggil getCourses() untuk mendapatkan data terbaru
      getCourses();
        toast.success("Course Deleted Succesfully")

    };

    const filter = reactive({
      ordering: 5,
      category: "",
      search: "",
      page: 1,
    });

    watch(filter, () => {
      getCourses();
    });

    // fungsi pagination
    const pagination = async (url) => {
      // kita set default lastChar = 1
      var lastChar = 1;

      // jika parameter url memiliki value
      if (url) {
        // ambil 1 string paling ujung dari value url
        lastChar = url.substr(url.length - 1);
      }

      // set reactive filter property page dengan value dari lastchar
      filter.page = lastChar;

      // panggil get course
      getCourses();
    };

    return {
      state,
      getCategories,
      getCourses,
      createCourse,
      form,
      deleteCourse,
      filter,
      pagination,
    };
  },
};
</script>
