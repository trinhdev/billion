import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "./flash_sale/ProductCard";
import "./flash_sale/VerticalProducts";

Alpine.data("FlashSale", () => ({
    products: [],

    get hasAnyProduct() {
        return this.products.length !== 0;
    },

    init() {
        this.fetchProducts();
    },

    async fetchProducts() {
        const response = await axios.get(
            route("storefront.flash_sale_products.index")
        );

        this.products = response.data;

        this.$nextTick(() => {
            new Swiper(".daily-deals", this.swiperOptions());
        });
    },

    swiperOptions() {
        return {
            modules: [Navigation],
            slidesPerView: 1,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 1,
                },
            },
        };
    },
}));
