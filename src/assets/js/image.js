
const imageMethods = {
    data() {
        return {
            action: HOST + "upload/up.php",
            currentImage: "",
            success: false,
            showImage:"",
        }
    },
    methods: {
        handleAvatarSuccess(res, file) {
            if (this.currentImage != this.form.image) {
                this.deleteImage(this.form.image)
            }
            this.showImage = URL.createObjectURL(file.raw);
            this.form.image = res
            this.form.image_full = URL.createObjectURL(file.raw)
            console.log(res);
        },
        beforeAvatarUpload(file) {
            const isJPG = file.type === "image/jpeg";
            const isLt2M = file.size / 1024 / 1024 < 2;
            
            if (!isJPG) {
                this.$message.error("Uploading pictures can only be JPG format!");
            }
            if (!isLt2M) {
                this.$message.error("The size of the uploaded picture can't exceed 2MB!");
            }
            return isJPG && isLt2M;
        },
        deleteImage(image) {
            let data = {
                params: {
                    image: image
                }
            };
            this.apiGet("upload/delete.php", data).then(res => { });
        },
        destroyedDeleteImage() {
            if (!this.success) {
                if (this.form.image != this.currentImage) {
                    this.deleteImage(this.form.image)
                }
            }
        },
        updateDeleteImage() {
                if (this.currentImage) {
                    this.deleteImage(this.currentImage)
                }
        },
        recoveryImage() {
            if (this.currentImage == this.form.image) {
                this.form.image = ""
                this.form.image_full = ""
                this.showImage = ""
            }else{
                this.deleteImage(this.form.image)
                this.showImage = ""
            }

        }
    },
    computed: {

    }
}

export default imageMethods
