const messages = {
    required: 'This field is required.',
    email: 'Enter valid Email Address.',
    max: 'This field must not be greater than 255.',
}

const rules = {
    email: [
        { required: true, message: messages.required, trigger: 'blur' },
        { type: 'email', message: messages.email, trigger: 'blur' },
        { max: 255, message: messages.max, trigger: 'blur' },
    ],
    password: [
        { required: true, message: messages.required, trigger: 'blur' },
        { max: 255, message: messages.max, trigger: 'blur' },
    ],
}

export default rules;
