<?php
/**
 * [The introduction of this file]
 *
 * @author     shixi_zhiqun, Weibo Team <shixi_zhiqun@staff.weibo.com>
 * @copyright  copyright(2013) weibo.com all rights reserved
 * @version    0.1
 */

namespace App\Http\Controllers;

use App;
use App\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    protected $table = 'books';

    /**
     * Show all the books.
     *
     * @return \Response
     */
    public function getBooks()
    {
        $books = Book::paginate(18);
        $booksCount = Book::count();
        $navType = 1;
        $pageTitle = '微博图书馆-首页';

        return view('page.browse.books',compact('books', 'navType', 'pageTitle', 'booksCount'));
    }

    /**
     * Show printed books.
     *
     * @return \Response
     */
    public function getPrintedBooks()
    {
        $books_builder = Book::whereIn('has_type',[0,2]);
        $booksCount = $books_builder->count();
        $books = $books_builder->paginate(18);
        $navType = 2;
        $pageTitle = '微博图书馆-实体书';

        return view('page.browse.books',compact('books', 'navType', 'pageTitle', 'booksCount'));
    }


    /**
     * Show ebooks.
     *
     * @return \Response
     */
    public function getEBooks()
    {
        $books_builder = Book::whereIn('has_type',[1,2]);
        $booksCount = $books_builder->count();
        $books = $books_builder->paginate(18);
        $navType = 3;
        $pageTitle = '微博图书馆-电子书';

        return view('page.browse.books',compact('books', 'navType', 'pageTitle', 'booksCount'));
    }

    /**
     * find a book by id.
     *
     * @param string $id id of the book
     * @return \Response
     */
    public function getBook($id)
    {
        $book = Book::find($id);
        $pbooks_count = 0;
        $ebooks_count = 0;
        if($book->contributions->count()) {
            foreach($book->contributions as $key => $contribution) {
                if($contribution->has_type == 0) {
                    $pbooks_count++;
                } else {
                    $ebooks_count++;
                }
            }
        };
        $book['pbooks_count'] = $pbooks_count;
        $book['ebooks_count'] = $ebooks_count;

        if($book) {
            $navType = 0;
            $pageTitle = '微博图书馆-'.$book->book_name;

            return view('page.book.info',compact('book', 'navType', 'pageTitle'));
        }

        return view('errors.404');
    }

    /**
     * Show books with searching.
     *
     * @param Request $request id of the book
     * @return \Response
     */
    public function getBooksFromSearch(Request $request)
    {
        $term = $request->input('term');
        $books_builder = Book::Where('book_name', 'LIKE', '%'.$term.'%')
            ->orWhere('isbn', 'LIKE', '%'.$term.'%')
            ->orWhere('author', 'LIKE', '%'.$term.'%')
            ->orWhere('publisher', 'LIKE', '%'.$term.'%')
            ->orderBy('created_at', 'desc')
            ->orderBy('book_name', 'asc');

        $books = $books_builder->paginate(18);
        $booksCount = $books_builder->count();
        $navType = 0;
        $pageTitle = '微博图书馆-搜索';
        return view('page.browse.books',compact('books', 'navType', 'pageTitle', 'booksCount'));
    }

    /**
     * find all the contributions of a book by id.
     *
     * @param string $id id of the book
     * @return \Response
     */
    public function getContributions($id)
    {
        $contributions=Book::findOrFail($id)->contributions;

        return view('page.user.contributions',compact('contributions'));
    }
}