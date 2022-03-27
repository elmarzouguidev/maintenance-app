<?php

declare(strict_types=1);

namespace App\Http\Controllers\Administration\Backup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backup\DownloadBackupFileRequest;
use App\Jobs\Backup\CreateBackupJob;
use App\Models\User;

use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Helpers\Format;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $admins = User::all();
        return view('theme.pages.Backup.index', compact('admins'));
    }

    public function backup()
    {
    }

    public function getFiles()
    {

        //$this->validateActiveDisk();

        $backupDestination = BackupDestination::create('backup', config('backup.backup.name'));

        $files = Cache::remember("backups-app-", now()->addSeconds(4), function () use ($backupDestination) {
            return $backupDestination
                ->backups()
                ->map(function (Backup $backup) {
                    $size = method_exists($backup, 'sizeInBytes') ? $backup->sizeInBytes() : $backup->size();

                    return [
                        'path' => $backup->path(),
                        'date' => $backup->date()->format('Y-m-d H:i:s'),
                        'size' => Format::humanReadableSize($size),
                    ];
                })
                ->toArray();
        });

        // return view('theme.pages.Backup.index', compact('files'));

        return view('theme.pages.Excel.index', compact('files'));
    }

    public function downloadFile(DownloadBackupFileRequest $request)
    {

        $backupDestination = BackupDestination::create('backup', config('backup.backup.name'));

        $backup = $backupDestination->backups()->first(function (Backup $backup) use ($request) {
            return $backup->path() === $request->fileName;
        });

        if (!$backup) {
            return response('Backup not found', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->respondWithBackupStream($backup);
    }

    public function respondWithBackupStream(Backup $backup): StreamedResponse
    {
        $fileName = pathinfo($backup->path(), PATHINFO_BASENAME);
        $size = method_exists($backup, 'sizeInBytes') ? $backup->sizeInBytes() : $backup->size();

        $downloadHeaders = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type' => 'application/zip',
            'Content-Length' => $size,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'public',
        ];

        return response()->stream(function () use ($backup) {
            $stream = $backup->stream();

            fpassthru($stream);

            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, $downloadHeaders);
    }

    public function makeBackup(string $option = 'only-db')
    {
        dispatch(new CreateBackupJob($option));
        //->onQueue(config('laravel_backup_panel.queue'));
        return redirect()->back()->with('success', "le backup a été crée ...");
    }

    public function deleteBackup(DownloadBackupFileRequest $request)
    {

        $backupDestination = BackupDestination::create('backup', config('backup.backup.name'));

        $backupDestination
            ->backups()
            ->first(function (Backup $backup) use ($request) {
                return $backup->path() === $request->fileName;
            })
            ->delete();

        $file = Storage::disk('google')->listContents();

        Storage::disk('google')->delete($file[0]['path']);

        Cache::forget('backups-app-');

        return redirect()->back()->with('success', "le backup a été supprimer ...");
    }
}
