const listMethods = {
    methods: {
        search() {
            router.push({
                path: this.$route.path,
                query: { keywords: this.keywords, page: 1 }
            });
        },
        //换页事件
        handleCurrentChange(page) {
            router.push({
                path: this.$route.path,
                query: { keywords: this.keywords, page: page }
            });
        },
        getCurrentPage() {
            let data = this.$route.query;
            if (data) {
                if (data.page) {
                    this.currentPage = parseInt(data.page);
                } else {
                    this.currentPage = 1;
                }
            }
        },
        //获取关键值
        getKeywords() {
            let data = this.$route.query;
            if (data) {
                if (data.keywords) {
                    this.keywords = data.keywords;
                } else {
                    this.keywords = "";
                }
            }
        },
    }
}

export default listMethods
