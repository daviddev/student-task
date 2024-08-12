const messages = {
    required: 'This field is required.',
}

const rules = {
    studentId: [
        { required: true, message: messages.required, trigger: 'blur' },
    ],
    startTime: [
        { required: true, message: messages.required, trigger: 'blur' },
    ],
    endTime: [
        { required: true, message: messages.required, trigger: 'blur' },
    ],
}

export default rules;
