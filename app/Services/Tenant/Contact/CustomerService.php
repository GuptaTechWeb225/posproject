<?php

namespace App\Services\Tenant\Contact;

use App\Models\Tenant\Customer\Customer;
use App\Services\Tenant\TenantService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CustomerService extends TenantService
{
    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }

    public function store(): Customer
    {
        $this->model = $this->save(
            $this->getAttrs('first_name', 'last_name', 'tin', 'customer_group_id')
        );
        return $this->model;
    }

    public function storeContacts(): void
    {
        $emailDetails = $this->getAttr('email_details');
        $this->storeContactsEmail($emailDetails, $this->store()->id);

        $phoneNumberDetails = $this->getAttr('phone_number_details');
        $this->storeContactsPhoneNumber($phoneNumberDetails, $this->store()->id);
    }

    public function storeAddress(): void
    {
        $addresses = $this->getAttr('address_information_details');

        $addressInfo = [];

        foreach ($addresses as $key => $address) {

            $addressInfo[] = $this->addressInfo($address, $this->store()->id);
        }

        $this->store()
            ->addresses()
            ->insert($addressInfo);
    }

    private function storeContactsEmail($emailDetails, $customer_id): void
    {
        $emailInfo = [];
        foreach ($emailDetails as $key => $emailData) {
            if (array_key_first($emailDetails[$key]) === 'email') {
                $emailInfo[] = $this->contactInfo(
                    array_key_first($emailDetails[$key]),
                    $emailData['email'],
                    $emailData['email_type'] ?? null,
                    $customer_id
                );
            }
        }
        $this->store()->contacts()->insert($emailInfo);
    }

    private function storeContactsPhoneNumber($phoneNumberDetails, $customer_id): void
    {
        $phoneNumberInfo = [];
        foreach ($phoneNumberDetails as $key => $phoneNumberData) {
            if (array_key_first($phoneNumberDetails[$key]) === 'phone_number') {
                $phoneNumberInfo[] = $this->contactInfo(
                    array_key_first($phoneNumberDetails[$key]),
                    $phoneNumberData['phone_number'],
                    $phoneNumberData['phone_number_type'] ?? null,
                    $customer_id
                );
            }
        }

        $this->store()->contacts()->insert($phoneNumberInfo);
    }


    private function contactInfo($key, $value, $type, $customer_id): array
    {
        return [

            'contactable_id' => $customer_id,
            'contactable_type' => Customer::class,
            'name' => $key,
            'value' => $value,
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    private function addressInfo($address, $customer_id): array
    {
        return [
            'customer_id' => $customer_id,
            'name' => $address['name'],
            'country_id' => $address['country_id'] ?? null,
            'city' => $address['city'] ?? null,
            'zip_code' => $address['zip_code'] ?? null,
            'area' => $address['area'] ?? null,
            'details' => $address['details'],
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function updateContacts(): void
    {
        $this->getModel()->contacts()->delete();

        $emailDetails = $this->getAttr('email_details');
        $this->storeContactsEmail($emailDetails, $this->getModel()->id);

        $phoneNumberDetails = $this->getAttr('phone_number_details');
        $this->storeContactsPhoneNumber($phoneNumberDetails, $this->getModel()->id);
    }

    public function updateAddress(): void
    {
        $this->getModel()->addresses()->delete();

        $addresses = $this->getAttr('address_information_details');

        $addressInfo = [];

        foreach ($addresses as $key => $address) {

            $addressInfo[] = $this->addressInfo($address, $this->getModel()->id);
        }

        $this->getModel()
            ->addresses()
            ->insert($addressInfo);
    }



    public function deleteCustomer(): void
    {
        $this->getModel()->contacts()->delete();

        $this->getModel()->addresses()->delete();

        $this->getModel()->delete();
    }

    public function storeThumbnail($user = null)
    {
        $user = $user ?? auth()->user();

        $this->deleteImage(optional($user->profilePicture)->path);

        $file_path = $this->uploadImage(
            request()->file('profile_picture'),
            config('file.profile_picture.folder'),
            config('file.profile_picture.height')
        );

        $user->profilePicture()->updateOrCreate([
            'type' => 'profile_picture'
        ], [
            'path' => $file_path
        ]);

        return $user->load('profilePicture');

    }
    public function uploadImage(UploadedFile $uploadedFile = null, $folder = "logo", $height = 300): ?string
    {
        if (is_null($uploadedFile))
            return null;
        $file = $this->saveImage($uploadedFile, $folder, $height);
        if ($file->success)
            return Storage::url($file->path);
        return null;
    }

    public function saveImage(UploadedFile $file, $subdirectory = 'logo', $height = 300): object
    {
        try {
            $file_path = $subdirectory . '/' . uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::put($this->storagePrefix . '/' . $file_path, $this->makeImage($file, $height)->__toString(), 'public');
            return (object)["success" => true, "message" => "File has been uploaded successfully", "path" => $file_path];
        } catch (Exception $exception) {
            $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($this->storagePrefix . '/' . $subdirectory, $file_name);
            return (object)["success" => true, "message" => "File has been uploaded successfully", "path" => $subdirectory . '/' . $file_name];
        }
    }

    public function makeImage(UploadedFile $file, $height = 300): \Intervention\Image\Image
    {
        return Image::make($file)->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save();
    }

    public function deleteImage(string $path = null): bool
    {
        return $this->deleteFile($path);
    }
}
