<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class PaginationService
{
    /**
     * Paginate a collection
     */
    public static function paginateCollection($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }

    /**
     * Get pagination options for different contexts
     */
    public static function getPaginationOptions($context = 'default')
    {
        $options = [
            'path' => request()->url(),
            'pageName' => 'page',
        ];

        switch ($context) {
            case 'admin':
                $options['path'] = request()->url();
                $options['pageName'] = 'page';
                break;
            case 'api':
                $options['path'] = request()->url();
                $options['pageName'] = 'page';
                break;
        }

        return $options;
    }

    /**
     * Get pagination settings from request
     */
    public static function getPaginationSettings($request, $defaultPerPage = 15)
    {
        $perPage = $request->get('per_page', $defaultPerPage);
        $page = $request->get('page', 1);
        
        // Limit per page to prevent abuse
        $perPage = min($perPage, 100);
        $perPage = max($perPage, 1);
        
        return [
            'per_page' => (int) $perPage,
            'page' => (int) $page,
        ];
    }

    /**
     * Format pagination response for API
     */
    public static function formatApiResponse($paginator)
    {
        return [
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ]
        ];
    }

    /**
     * Get pagination info for frontend
     */
    public static function getPaginationInfo($paginator)
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'has_more_pages' => $paginator->hasMorePages(),
            'showing' => $paginator->firstItem() . ' - ' . $paginator->lastItem() . ' จาก ' . $paginator->total() . ' รายการ',
        ];
    }
}
