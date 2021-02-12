import lozad from 'lozad'

export default {
    created() {
        if (this.$environment === 'production' && this.$optimizeImages) {
            this.setImgUrl()
        }
    },
    methods: {
        setImgUrl() {
            const observer = new MutationObserver(function (mutations) {
                for (let mutation of mutations) {
                    for (let node of mutation.addedNodes) {

                        if (node.hasChildNodes()) {

                            let images = node.getElementsByTagName('img')
                            for (let image of images) {

                                if (!('preview' in image.dataset) && (!image.src.startsWith('blob') && !image.src.startsWith('data'))) {

                                    let options = 'cdn'
                                    let format = (image.src.split('.').pop()).split('?').shift()

                                    Modernizr.on('webp', result => {

                                        format = !!result ? 'webp' : format

                                        image.src = `https://img.imageboss.me/${options}/progressive:false,format:${format}/${image.src}`

                                        image.setAttribute('data-src', image.src)

                                        const lozadObserver = lozad(image, {
                                            rootMargin: '60px 0px',
                                            threshold: 0.1,
                                        })
                                        lozadObserver.observe()
                                    })
                                }
                            }
                        }
                    }
                }
            })

            observer.observe(document.body, {childList: true, subtree: true})
        }
    }
}
