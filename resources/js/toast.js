import iziToast from 'izitoast'
import 'izitoast/dist/css/iziToast.min.css'

export default (type, message, options = {}) => {
    const isQuestion = type === 'question'

    return new Promise((resolve, reject) => {
        iziToast[type]({
            close: false,
            progressBar: false,
            overlay: isQuestion,
            displayMode: isQuestion ? 'once' : 'replace',
            zindex: 999,
            closeOnEscape: true,
            overlayClose: true,
            message,
            position: isQuestion ? 'center' : 'bottomRight',
            timeout: isQuestion ? false : 3000,
            title: '',
            buttons: isQuestion ?   [
                ['<button><b>Yes</b></button>', (instance, toast) => {
                    instance.hide({ transitionOut: 'fadeOut' }, toast);
                    resolve()
                }, true],
                ['<button>No</button>', (instance, toast) => {
                    instance.hide({ transitionOut: 'fadeOut' }, toast);
                    reject()
                }],
            ] : [],
            ...options
        });
    })
}
