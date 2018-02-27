<template>
    <v-menu
        ref="menu"
        lazy
        :close-on-content-click="false"
        v-model="menu"
        transition="scale-transition"
        offset-y
        full-width
        :nudge-right="40"
        min-width="290px">
        <slot></slot>
        <v-text-field
          v-model="value.birthdate"
          :value="value.birthdate"
          ref="birthdate"
          slot="activator"
          label="Birthdate"
          hint="yyyy-mm-dd"
          :name="'birthdate-' + index.room + '-' + index.traveler"
          :id="'birthdate-' + index.room  + '-' + index.traveler"
          v-validate="'required'"
          :error-messages="errors.collect('birthdate-' + index.room  + '-' + index.traveler)"
          :data-vv-name="'birthdate-' + index.room  + '-' + index.traveler"
          data-vv-as="Birthdate"
          @birthdate="updateBirthdate()"
          readonly
          required></v-text-field>
        <v-date-picker
          ref="picker"
          v-model="value.birthdate"
          @change="save"
          min="1930-01-01"
          :max="new Date().toISOString().substr(0, 10)"></v-date-picker>
      </v-menu>
</template>

<script>
// Mixins
import Colorable from 'vuetify/src/mixins/colorable'
import Input from '../mixins/input'

export default {
    name: 'birthdate',
    inject: ['$validator'],
    mixins: [
      Colorable,
      Input,
    ],

    props: {
        value: Object,
        index: Object,
        autofocus: Boolean,
        autoGrow: Boolean,
        box: Boolean,
        clearable: Boolean,
        color: {
          type: String,
          default: 'primary'
        },
        counter: [Boolean, Number, String],
        fullWidth: Boolean,
        multiLine: Boolean,
        noResize: Boolean,
        placeholder: String,
    },

    data() {
        return {
            date: null,
            menu: false,

            initialValue: null,
            inputHeight: null,
            internalChange: false,
            badInput: false
        }
    },

    computed: {
        classes () {
          const classes = {
            ...this.genSoloClasses(),
            'input-group--text-field': true,
            'input-group--text-field-box': this.box,
            'input-group--single-line': this.singleLine || this.isSolo,
            'input-group--multi-line': this.multiLine,
            'input-group--full-width': this.fullWidth,
            'input-group--no-resize': this.noResizeHandle,
            'input-group--prefix': this.prefix,
            'input-group--suffix': this.suffix,
          }

          if (this.hasError) {
            classes['error--text'] = true
          } else {
            return this.addTextColorClassChecks(classes)
          }

          return classes
        },
        count () {
          let inputLength
          if (this.inputValue) inputLength = this.inputValue.toString().length
          else inputLength = 0

          return `${inputLength} / ${this.counterLength}`
        },
        counterLength () {
          const parsedLength = parseInt(this.counter, 10)
          return isNaN(parsedLength) ? 25 : parsedLength
        },
        inputValue: {
          get () {
            return this.lazyValue
          },
          set (val) {
            if (this.mask) {
              this.lazyValue = this.unmaskText(this.maskText(this.unmaskText(val)))
              this.setSelectionRange()
            } else {
              this.lazyValue = val
              this.$emit('input', this.lazyValue)
            }
          }
        },
        isDirty () {
          return (this.lazyValue != null &&
            this.lazyValue.toString().length > 0) ||
            this.badInput ||
            ['time', 'date', 'datetime-local', 'week', 'month'].includes(this.type)
        },
        noResizeHandle () {
          return this.isTextarea && (this.noResize || this.shouldAutoGrow)
        },
        shouldAutoGrow () {
          return this.isTextarea && this.autoGrow
        }
    },

    watch: {
        menu(val) {
            val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
        },
        isFocused (val) {
          if (val) {
            this.initialValue = this.lazyValue
          } else if (this.initialValue !== this.lazyValue) {
            this.$emit('change', this.lazyValue)
          }
        },
        value (val) {
          if (this.mask && !this.internalChange) {
            const masked = this.maskText(this.unmaskText(val))
            this.lazyValue = this.unmaskText(masked)

            // Emit when the externally set value was modified internally
            String(val) !== this.lazyValue && this.$nextTick(() => {
              this.$refs.input.value = masked
              this.$emit('input', this.lazyValue)
            })
          } else this.lazyValue = val

          if (this.internalChange) this.internalChange = false

          !this.validateOnBlur && this.validate()
          this.shouldAutoGrow && this.calculateInputHeight()
        }
    },

    mounted () {
      this.shouldAutoGrow && this.calculateInputHeight()
      this.autofocus && this.focus()
    },

    methods: {
        updateBirthdate() {
            this.$emit('birthdate', {
                birthdate: +this.$refs.birthdate.value,
            })
        },
        save(date) {
            this.$refs.menu.save(date)
        },
        calculateInputHeight () {
            this.inputHeight = null

            this.$nextTick(() => {
              const height = this.$refs.input
                ? this.$refs.input.scrollHeight
                : 0
              const minHeight = parseInt(this.rows, 10) * parseFloat(this.rowHeight)
              this.inputHeight = Math.max(minHeight, height)
            })
        },
        onInput (e) {
            this.mask && this.resetSelections(e.target)
            this.inputValue = e.target.value
            this.badInput = e.target.validity && e.target.validity.badInput
            this.shouldAutoGrow && this.calculateInputHeight()
        },
        blur (e) {
            this.isFocused = false
            // Reset internalChange state
            // to allow external change
            // to persist
            this.internalChange = false

            this.$nextTick(() => {
              this.validate()
            })
            this.$emit('blur', e)
        },
        focus (e) {
            if (!this.$refs.input) return

            this.isFocused = true
            if (document.activeElement !== this.$refs.input) {
              this.$refs.input.focus()
            }
            this.$emit('focus', e)
        },
        keyDown (e) {
            // Prevents closing of a
            // dialog when pressing
            // enter
            if (this.isTextarea &&
              this.isFocused &&
              e.keyCode === 13
            ) {
              e.stopPropagation()
            }

            this.internalChange = true
        },
        genCounter () {
            return this.$createElement('div', {
              'class': {
                'input-group__counter': true,
                'input-group__counter--error': this.hasError
              }
            }, this.count)
        },
        genInput () {
            const tag = this.isTextarea ? 'textarea' : 'input'
            const listeners = Object.assign({}, this.$listeners)
            delete listeners['change'] // Change should not be bound externally

            const data = {
              style: {},
              domProps: {
                value: this.maskText(this.lazyValue)
              },
              attrs: {
                ...this.$attrs,
                autofocus: this.autofocus,
                disabled: this.disabled,
                required: this.required,
                readonly: this.readonly,
                tabindex: this.tabindex,
                'aria-label': (!this.$attrs || !this.$attrs.id) && this.label // Label `for` will be set if we have an id
              },
              on: Object.assign(listeners, {
                blur: this.blur,
                input: this.onInput,
                focus: this.focus,
                keydown: this.keyDown
              }),
              ref: 'input'
            }

            if (this.shouldAutoGrow) {
              data.style.height = this.inputHeight && `${this.inputHeight}px`
            }

            if (this.placeholder) data.attrs.placeholder = this.placeholder

            if (!this.isTextarea) {
              data.attrs.type = this.type
            } else {
              data.attrs.rows = this.rows
            }

            if (this.mask) {
              data.attrs.maxlength = this.masked.length
            }

            const children = [this.$createElement(tag, data)]

            this.prefix && children.unshift(this.genFix('prefix'))
            this.suffix && children.push(this.genFix('suffix'))

            return children
        },
        genFix (type) {
            return this.$createElement('span', {
              'class': `input-group--text-field__${type}`
            }, this[type])
        },
        clearableCallback () {
            this.inputValue = null
            this.$nextTick(() => this.$refs.input.focus())
        }
    },

    render () {
      return this.genInputGroup(this.genInput(), { attrs: { tabindex: false } })
    }
}
</script>
