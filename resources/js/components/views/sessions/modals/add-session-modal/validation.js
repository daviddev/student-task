const messages = {
    required: 'This field is required.',
}

const rules = {
    studentId: [
        { required: true, message: messages.required, trigger: 'blur' },
    ],
    type: [
        { required: true, message: messages.required, trigger: 'blur' },
    ],
    date: [
        { required: true, message: messages.required, trigger: 'blur' },
    ],
    duration: [
        { required: true, message: messages.required, trigger: 'blur' },
    ],
}

export default rules;
