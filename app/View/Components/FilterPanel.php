class FilterPanel extends Component
{
    public $filters;
    
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function render()
    {
        return view('components.filter-panel');
    }
} 