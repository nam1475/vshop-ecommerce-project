<script setup>
import { defineProps, ref } from "vue";
import Main from "@/Pages/Admin/Components/Layout/Main.vue";
import Search from "@/Pages/Admin/Components/Search.vue";
import Action from "@/Pages/Admin/Components/Action.vue";
import Filter from "@/Pages/Admin/Components/Filter.vue";
import { Link, router } from "@inertiajs/vue3";
import { Plus } from "@element-plus/icons-vue";
import { success, error, warning } from "@/alert.js";

defineProps({
  products: Array,
  categories: Array,
  brands: Array,
});

function deleteProduct(product){
  warning()
    .then((result) => {
      if (result.isConfirmed) {
        try{
          router.delete(route("admin.product.delete", product.id), {
            onSuccess: (page) => {
              success(page);
            },
            onError: (page) => {
              error(page);
            },
          });
        } catch(err){
          console.log(err);
        }
      }
    });
}
</script>

<template>
  <Main>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
      <!-- Main modal -->
      <!-- <div
        id="crud-modal"
        tabindex="-1"
        aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
      >
        <div class="relative p-4 w-full max-w-4xl max-h-full">
          <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div
              class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600"
            >
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ editMode ? "Edit Product" : "Create Product" }}
              </h3>
              <button
                type="button"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-toggle="crud-modal"
              >
                <svg
                  class="w-3 h-3"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 14 14"
                >
                  <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                  />
                </svg>
              </button>
            </div>

            <form
              class="p-4 md:p-5"
              @submit.prevent="
                editMode == true ? updateProduct() : createProduct()
              "
            >
              <div class="grid gap-4 mb-4 grid-cols-3">
                <div class="col-span-1">
                  <label
                    for="name"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Name</label
                  >
                  <input
                    type="text"
                    name="name"
                    id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    required=""
                    v-model="form.name"
                  />
                </div>
                <div class="col-span-1">
                  <label
                    for="brand"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Brand</label
                  >
                  <select
                    id="brand"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    v-model="form.brand_id"
                  >
                    <option value=""></option>
                    <option
                      v-for="brand in brands"
                      :value="brand.id"
                      :key="brand.id"
                      :selected="brand.id == form.brand_id"
                    >
                      {{ brand.name }}
                    </option>
                  </select>
                </div>
                <div class="col-span-1">
                  <label
                    for="category"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Category</label
                  >
                  <select
                    id="category"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    v-model="form.category_id"
                  >
                    <option value=""></option>
                    <option
                      v-for="category in categories"
                      :value="category.id"
                      :key="category.id"
                      :selected="category.id == form.category_id"
                    >
                      {{ category.name }}
                    </option>
                  </select>
                </div>
                <div class="col-span-1">
                  <label
                    for="price"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Price</label
                  >
                  <input
                    type="number"
                    id="price"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    required=""
                    v-model="form.price"
                  />
                </div>
                <div class="col-span-1">
                  <label
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Published</label
                  >
                  <div>
                    <input
                      type="radio"
                      name="published"
                      id="published"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                      required=""
                      v-model="form.published"
                      :checked="form.published"
                    />
                    <label
                      for="published"
                      class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >Yes</label
                    >
                  </div>
                  <div>
                    <input
                      type="radio"
                      name="published"
                      id="no-published"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                      required=""
                      v-model="form.published"
                      :checked="!form.published"
                    />
                    <label
                      for="no-published"
                      class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >No</label
                    >
                  </div>
                </div>

                <div class="col-span-1">
                  <label
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >In stock</label
                  >
                  <div>
                    <input
                      type="radio"
                      name="in_stock"
                      id="in-stock"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                      required=""
                      v-model="form.in_stock"
                      :checked="form.in_stock"
                    />
                    <label
                      for="in-stock"
                      class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >Yes</label
                    >
                  </div>
                  <div>
                    <input
                      type="radio"
                      name="in_stock"
                      id="out-stock"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                      required=""
                      v-model="form.in_stock"
                      :checked="!form.published"
                    />
                    <label
                      for="out-stock"
                      class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >No</label
                    >
                  </div>
                </div>
                <div class="col-span-1">
                  <label
                    for="quantity"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Quantity</label
                  >
                  <input
                    type="number"
                    name="quantity"
                    id="quantity"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    v-model="form.quantity"
                  />
                </div>

                <div class="col-span-2">
                  <label
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Description</label
                  >
                  <textarea
                    id="description"
                    rows="3"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    v-model="form.description"
                  ></textarea>
                </div>

                <div class="col-span-3">
                  <label
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Images</label
                  >
                  <el-upload
                    list-type="picture-card"
                    :on-preview="handlePictureCardPreview"
                    :on-remove="handleRemove"
                    :auto-upload="false"
                    :on-change="handleFileUpload"
                    :file-list="form.product_images"
                    multiple
                  >
                    <el-icon><Plus /></el-icon>
                  </el-upload>

                  <el-dialog v-model="dialogVisible">
                    <img w-full :src="dialogImageUrl" alt="Preview Image" />
                  </el-dialog>
                </div>
              </div>

              <button
                type="submit"
                class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
              >
                Submit
              </button>
            </form>
          </div>
        </div>
      </div> -->

      <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div
          class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden"
        >
          <div
            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
          >
            <!-- Search -->
            <Search />

            <div
              class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0"
            >
              <Link
                :href="route('admin.product.add')"
                class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800"
              >
                <svg
                  class="h-3.5 w-3.5 mr-2"
                  fill="currentColor"
                  viewbox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true"
                >
                  <path
                    clip-rule="evenodd"
                    fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                  />
                </svg>
                Add product
              </Link>

              <!-- Actions -->
              <div class="flex items-center space-x-3 w-full md:w-auto">
                <Action />

                <!-- Filter -->
                <Filter />
              </div>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table
              class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
            >
              <thead
                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
              >
                <tr>
                  <th scope="col" class="px-4 py-3">ID</th>
                  <th scope="col" class="px-4 py-3">Name</th>
                  <th scope="col" class="px-4 py-3">Slug</th>
                  <th scope="col" class="px-4 py-3">Category</th>
                  <th scope="col" class="px-4 py-3">Brand</th>
                  <th scope="col" class="px-4 py-3">Price</th>
                  <th scope="col" class="px-4 py-3">Quantity</th>
                  <th scope="col" class="px-4 py-3">Stock</th>
                  <th scope="col" class="px-4 py-3">Published</th>
                  <th scope="col" class="px-4 py-3">Created at</th>
                  <th scope="col" class="px-4 py-3">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  class="border-b dark:border-gray-700"
                  v-for="product in products"
                  :key="product.id"
                >
                  <th
                    scope="row"
                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    {{ product.id }}
                  </th>
                  <td class="px-4 py-3">{{ product.name }}</td>
                  <td class="px-4 py-3">{{ product.slug }}</td>
                  <td class="px-4 py-3">
                    {{ product.category == null ? "-" : product.category.name }}
                  </td>
                  <td class="px-4 py-3">
                    {{ product.brand == null ? "-" : product.brand.name }}
                  </td>
                  <td class="px-4 py-3">{{ product.price }}</td>
                  <td class="px-4 py-3">{{ product.quantity }}</td>
                  <td class="px-4 py-3" v-if="product.in_stock">
                    <span
                      class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"
                    >
                      In stock
                    </span>
                  </td>
                  <td class="px-4 py-3" v-else>
                    <span
                      class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300"
                    >
                      Out of stock
                    </span>
                  </td>
                  <td class="px-4 py-3" v-if="product.published">
                    <span
                      class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"
                    >
                      Published
                    </span>
                  </td>
                  <td class="px-4 py-3" v-else>
                    <span
                      class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300"
                    >
                      Unpublished
                    </span>
                  </td>
                  <td class="px-4 py-3">{{ product.created_at }}</td>
                  <td class="px-4 py-3 flex items-center justify-end">
                    <button
                      :id="`${product.id}-button`"
                      :data-dropdown-toggle="`${product.id}-dropdown`"
                      class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                      type="button"
                    >
                      <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        fill="currentColor"
                        viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
                        />
                      </svg>
                    </button>
                    <div
                      :id="`${product.id}-dropdown`"
                      class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
                    >
                      <ul
                        class="py-1 text-sm text-gray-700 dark:text-gray-200"
                        :id="`${product.id}-button`"
                      >
                        <li>
                          <a
                            href="#"
                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                            >Show</a
                          >
                        </li>
                        <li>
                          <!-- <a
                            @click="editProduct(product)"
                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-target="crud-modal"
                            data-modal-toggle="crud-modal"
                            >Edit
                          </a> -->
                          <Link
                            :href="route('admin.product.edit', product.id)"
                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                            >Edit
                          </Link>
                        </li>
                      </ul>
                      <div class="py-1">
                        <a
                          @click="deleteProduct(product)"
                          href="#"
                          class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                          >Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <nav
            class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
            aria-label="Table navigation"
          >
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
              Showing
              <span class="font-semibold text-gray-900 dark:text-white"
                >1-10</span
              >
              of
              <span class="font-semibold text-gray-900 dark:text-white"
                >1000</span
              >
            </span>
            <ul class="inline-flex items-stretch -space-x-px">
              <li>
                <a
                  href="#"
                  class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >
                  <span class="sr-only">Previous</span>
                  <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    fill="currentColor"
                    viewbox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </a>
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                  >1</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                  >2</a
                >
              </li>
              <li>
                <a
                  href="#"
                  aria-current="page"
                  class="flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-50 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                  >3</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                  >...</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                  >100</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >
                  <span class="sr-only">Next</span>
                  <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    fill="currentColor"
                    viewbox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </section>
  </Main>
</template>