<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: Object,
});

function shouldShow(index, total, currentIndex) {
    // Always show Previous and Next
    if (index === 0 || index === total - 1) return true;

    // Get page links (exclude Previous/Next)
    const pageIndex = index - 1;
    const totalPages = total - 2;

    // Show first and last page
    if (pageIndex === 0 || pageIndex === totalPages - 1) return true;

    // Show pages around current
    if (Math.abs(pageIndex - currentIndex) <= 1) return true;

    return false;
}

function shouldShowEllipsis(index, total, currentIndex) {
    const pageIndex = index - 1;

    // Show ellipsis after first page
    if (pageIndex === 1 && currentIndex > 2) return true;

    // Show ellipsis before last page
    if (pageIndex === total - 3 && currentIndex < total - 4) return true;

    return false;
}

function getCurrentIndex() {
    if (!props.links) return 0;
    const pageLinks = props.links.slice(1, -1);
    return pageLinks.findIndex(link => link.active);
}
</script>

<template>
    <nav v-if="links && links.length > 3" class="flex items-center justify-center gap-2 mt-6">
        <template v-for="(link, index) in links" :key="index">
            <!-- Show link -->
            <template v-if="shouldShow(index, links.length, getCurrentIndex())">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="px-3 py-2 text-sm border rounded"
                    :class="link.active ? 'bg-blue-500 text-white' : 'bg-white hover:bg-gray-100'"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="px-3 py-2 text-sm border rounded"
                    :class="link.active ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-400'"
                    v-html="link.label"
                />
            </template>

            <!-- Show ellipsis -->
            <span v-else-if="shouldShowEllipsis(index, links.length, getCurrentIndex())" class="px-2">
                ...
            </span>
        </template>
    </nav>
</template>
