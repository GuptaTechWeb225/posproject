<?php


namespace App\Services\Tenant\Expense;


use App\Helpers\Core\Traits\FileHandler;
use App\Models\Tenant\Expense\Expense;
use App\Services\Tenant\TenantService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ExpenseService extends TenantService
{
    use FileHandler;

    public function __construct(Expense $expense)
    {
        $this->model = $expense;
    }

    public function storeAttachments()
    {
        $file_path = [];
        $oldImage = 0;

        if (request()->hasFile('attachments')) {

            foreach ($this->getAttr('attachments') as $attachment) {

                $file_path[] = [
                    'path' => $this->isWithOriginalName()->storeFile($attachment, 'expense'),
                    'fileable_type' => Expense::class,
                    'fileable_id' => $this->model->id,
                    'type' => 'expense',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $this->model->attachments()->insert($file_path);
            $oldImage = 1;
        }

        if ($oldImage === 0) {
            $this->prepareDeleteAttachment();
        }
    }

    public function prepareDeleteAttachment()
    {
        $paths = $this->model->attachments()->pluck('path')->filter(function ($path) {
            return !in_array($path, request()->get('dont_delete', []));
        })->toArray();

        $this->deleteAttachments($paths);
    }

    public function deleteAttachments($paths = [])
    {
        $this->deleteMultipleFile($paths);

        return $this->model->attachments()
            ->whereIn('path', $paths)
            ->delete();
    }

    public function generateUploadingFileName(UploadedFile $file): string
    {
        $name = $this->getDefaultName();

        if ($this->isOriginalName) {
            $name = Str::of($file->getClientOriginalName())
                ->replaceMatches("/[.].*/", '')
                ->snake()
                ->limit(30, '');

            $name = $name . '*' . uniqid() . '*';
        }

        return $name . "." . $file->getClientOriginalExtension();
    }


    public function deleteAttachmentFile($attachments): static
    {
        foreach ($attachments as $attachment) {
            $this->deleteImage($attachment->path);
        }
        $this->model->attachments()->delete();

        return $this;
    }
}