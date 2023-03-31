<?php

namespace WalkerChiu\MorphAddress\Models\Forms;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use WalkerChiu\Core\Models\Forms\FormRequest;

class AddressFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = $this->all();
        if (
            $request->isMethod('put')
            && empty($data['id'])
            && isset($request->id)
        ) {
            $data['id'] = (string) $request->id;
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return Array
     */
    public function attributes()
    {
        return [
            'morph_type'    => trans('php-morph-address::system.morph_type'),
            'morph_id'      => trans('php-morph-address::system.morph_id'),
            'type'          => trans('php-morph-address::system.type'),
            'phone'         => trans('php-morph-address::system.phone'),
            'email'         => trans('php-morph-address::system.email'),
            'area'          => trans('php-morph-address::system.area'),
            'is_main'       => trans('php-morph-address::system.is_main'),

            'name'          => trans('php-morph-address::system.name'),
            'address_line1' => trans('php-morph-address::system.address_line1'),
            'address_line2' => trans('php-morph-address::system.address_line2'),
            'guide'         => trans('php-morph-address::system.guide')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        $rules = [
            'morph_type'    => 'required_with:morph_id|string',
            'morph_id'      => 'required_with:morph_type|string',
            'type'          => ['required', Rule::in(config('wk-core.class.morph-address.addressType')::getCodes())],
            'phone'         => '',
            'email'         => 'email',
            'area'          => ['required', Rule::in(config('wk-core.class.core.countryZone')::getCodes())],
            'is_main'       => 'boolean',

            'name'          => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'guide'         => 'nullable|string|max:255'
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','string','exists:'.config('wk-core.table.morph-address.addresses').',id']]);
        } elseif ($request->isMethod('post')) {
            $rules = array_merge($rules, ['id' => ['nullable','string','exists:'.config('wk-core.table.morph-address.addresses').',id']]);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.required'              => trans('php-core::validation.required'),
            'id.string'                => trans('php-core::validation.string'),
            'id.exists'                => trans('php-core::validation.exists'),
            'morph_type.required_with' => trans('php-core::validation.required_with'),
            'morph_type.string'        => trans('php-core::validation.string'),
            'morph_id.required_with'   => trans('php-core::validation.required_with'),
            'morph_id.string'          => trans('php-core::validation.string'),
            'type.required'            => trans('php-core::validation.required'),
            'type.in'                  => trans('php-core::validation.in'),
            'email.max'                => trans('php-core::validation.email'),
            'area.required'            => trans('php-core::validation.required'),
            'area.in'                  => trans('php-core::validation.in'),
            'is_main.boolean'          => trans('php-core::validation.boolean'),

            'name.required'          => trans('php-core::validation.required'),
            'name.string'            => trans('php-core::validation.string'),
            'name.max'               => trans('php-core::validation.max'),
            'address_line1.required' => trans('php-core::validation.required'),
            'address_line1.string'   => trans('php-core::validation.string'),
            'address_line1.max'      => trans('php-core::validation.max'),
            'address_line2.string'   => trans('php-core::validation.string'),
            'address_line2.max'      => trans('php-core::validation.max'),
            'guide.string'           => trans('php-core::validation.string'),
            'guide.max'              => trans('php-core::validation.max')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after( function ($validator) {
            $data = $validator->getData();
            if (
                isset($data['morph_type'])
                && isset($data['morph_id'])
            ) {
                if (
                    config('wk-morph-address.onoff.site-cms')
                    && !empty(config('wk-core.class.site-cms.site'))
                    && $data['morph_type'] == config('wk-core.class.site-cms.site')
                ) {
                    $result = DB::table(config('wk-core.table.site-cms.sites'))
                                ->where('id', $data['morph_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('morph_id', trans('php-core::validation.exists'));
                } elseif (
                    config('wk-morph-address.onoff.site-mall')
                    && !empty(config('wk-core.class.site-mall.site'))
                    && $data['morph_type'] == config('wk-core.class.site-mall.site')
                ) {
                    $result = DB::table(config('wk-core.table.site-mall.sites'))
                                ->where('id', $data['morph_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('morph_id', trans('php-core::validation.exists'));
                } elseif (
                    config('wk-morph-address.onoff.group')
                    && !empty(config('wk-core.class.group.group'))
                    && $data['morph_type'] == config('wk-core.class.group.group')
                ) {
                    $result = DB::table(config('wk-core.table.group.groups'))
                                ->where('id', $data['morph_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('morph_id', trans('php-core::validation.exists'));
                } elseif (
                    config('wk-morph-address.onoff.account')
                    && !empty(config('wk-core.class.account.profile'))
                    && $data['morph_type'] == config('wk-core.class.account.profile')
                ) {
                    $result = DB::table(config('wk-core.table.account.profiles'))
                                ->where('id', $data['morph_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('morph_id', trans('php-core::validation.exists'));
                } elseif (
                    !empty(config('wk-core.class.user'))
                    && $data['morph_type'] == config('wk-core.class.user')
                ) {
                    $result = DB::table(config('wk-core.table.user'))
                                ->where('id', $data['morph_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('morph_id', trans('php-core::validation.exists'));
                }
            }
        });
    }
}
