<header class="relative" >
    <nav x-data="{ open: false }" x-cloak @click.outside="open = false" @close.stop="open = false" class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
        <div class="flex items-center justify-between gap-x-4">
            <button @click="open = ! open" class="lg:hidden" ><x-icons.bars-3-bottom-left /></button>        
            <div>
                 Logo
            </div>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
            <a href="{{route('sponsorship.index')}}">Apadrinhe</a>
            <a href="{{route('adoption.index')}}">Adote</a>
            <a href="#">Sobre</a>
            <a href="#">Contato</a>
        </div>
        <a class="block rounded-md bg-fuchsia-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-fuchsia-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-fuchsia-600">
            Doe qualquer valor
        </a>
        <div x-show="open" class="fixed flex flex-col gap-y-6 inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm ">
            <div class="flex items-center justify-between gap-x-4 mb-6">
                <div>
                    Logo
                </div>
                <button @click="open = false" ><x-icons.x-mark /></button>        
            </div>
            <a href="{{route('sponsorship.index')}}">Apadrinhe</a>
            <a href="{{route('adoption.index')}}">Adote</a>
            <a href="#">Sobre</a>
            <a href="#">Contato</a>
            <a class="block w-full rounded-md bg-fuchsia-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-fuchsia-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-fuchsia-600">
                Doe qualquer valor
            </a>
        </div>
    </nav>
</header>