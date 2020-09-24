<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;

class ImportBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importbooks
                            {xml : Path to xml file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import books from xml file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $xmlFile = $this->argument('xml');
            $xml = simplexml_load_file($xmlFile);
            $count = count($xml->book);
            if (!$count) {
                throw new \Exception("Cannot find book entries");
            }
        } catch (\Exception $e) {
            $this->error("Failed to load the xml file!");
            $this->error($e->getMessage());
            return 2;
        }

        $success = 0;
        $failure = 0;

        $bookController = $controller = app()->make('App\Http\Controllers\BookController');

        for($i = 0; $i < $count; $i++) {
            try {
                $book = $xml->book[$i];
                $existing = Book::where('isbn', '=', $book->ISBN)->first();
                if ($existing) {
                    throw new \Exception(sprintf("ISBN: %s already exists", $book->ISBN));
                }
                $savedFileName = $bookController->storeImage($book->image);
                $newBook = new Book([
                    'title' => $book->title,
                    'isbn' => $book->ISBN,
                    'description' => $book->description,
                    'image' => '/storage/images/' . $savedFileName,
                ]);
                $newBook->save();
                $success++;
                $this->info("Successfully added the book with ISBN: ".$book->ISBN);
            } catch (\Exception $e) {
                $failure++;
                $this->error("Failed to added the book with ISBN: ".$book->ISBN);
                $this->error($e->getMessage());
            }
        }
        $this->line(sprintf("Total Count: %d, successes: %d, failures: %d", $count, $success, $failure));

        return 0;
    }

    // private downloadImage($url, )

    private function getXml()
    {
        $xml = $this->argument('xml');

        if (!file_exists($xml)) {
            throw new \Exception("Cannot find the file: '$xml'");
        }

        return $xml;
    }
}
