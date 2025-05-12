<?php  namespace App\Http\Controllers;  

use App\Models\Service; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;  

class ServiceController extends Controller {     
    /**      
     * Constructeur avec middleware pour autoriser uniquement le greffier en chef      
     */     
    public function __construct()     
    {         
        $this->middleware('role:greffier_en_chef');     
    }      
    
    /**      
     * Affiche la liste des services      
     */     
    public function index()     
    {         
        $services = Service::paginate(10);         
        return view('services.index', compact('services'));     
    }      
    
    /**      
     * Affiche le formulaire de création d'un service      
     */     
    public function create()     
    {         
        return view('services.create');     
    }      
    
    /**      
     * Enregistre un nouveau service      
     */     
    public function store(Request $request)     
    {         
        // Validation des champs         
        $validator = Validator::make($request->all(), [             
            'nom' => ['required', 'string', 'max:255', 'unique:services'],             
            'description' => ['nullable', 'string'],         
        ]);          
        
        if ($validator->fails()) {             
            return redirect()->back()                 
                ->withErrors($validator)                 
                ->withInput();         
        }          
        
        // Création du service         
        $service = Service::create([             
            'nom' => $request->nom,             
            'description' => $request->description,         
        ]);          
        
        if ($service) {             
            return redirect()->route('services.index')                 
                ->with('success', 'تم إنشاء الخدمة بنجاح.');         
        }          
        
        return redirect()->back()             
            ->with('error', 'حدث خطأ أثناء إنشاء الخدمة.')             
            ->withInput();     
    }      
    
    /**      
     * Affiche les détails d'un service      
     */     
    public function show(Service $service)     
    {         
        return view('services.show', compact('service'));     
    }      
    
    /**      
     * Affiche le formulaire de modification d'un service      
     */     
    public function edit(Service $service)     
    {         
        return view('services.edit', compact('service'));     
    }      
    
    /**      
     * Met à jour un service      
     */     
    public function update(Request $request, Service $service)     
    {         
        // Validation des champs         
        $validator = Validator::make($request->all(), [             
            'nom' => ['required', 'string', 'max:255', 'unique:services,nom,'.$service->id],             
            'description' => ['nullable', 'string'],         
        ]);          
        
        if ($validator->fails()) {             
            return redirect()->back()                 
                ->withErrors($validator)                 
                ->withInput();         
        }          
        
        // Mise à jour des informations du service         
        $service->update([             
            'nom' => $request->nom,             
            'description' => $request->description,         
        ]);          
        
        return redirect()->route('services.index')             
            ->with('success', 'تم تعديل الخدمة بنجاح.');     
    }      
    
    /**      
     * Supprime un service      
     */     
    public function destroy(Service $service)     
    {         
        // Vérification des utilisateurs associés         
        if ($service->users()->count() > 0) {             
            return redirect()->route('services.index')                 
                ->with('error', 'لا يمكن حذف هذه الخدمة لأن هناك مستخدمين مرتبطين بها.');         
        }          
        
        // Vérification des dossiers associés         
        if ($service->dossiers()->count() > 0) {             
            return redirect()->route('services.index')                 
                ->with('error', 'لا يمكن حذف هذه الخدمة لأن هناك ملفات مرتبطة بها.');         
        }          
        
        $service->delete();          
        
        return redirect()->route('services.index')             
            ->with('success', 'تم حذف الخدمة بنجاح.');     
    } 
}